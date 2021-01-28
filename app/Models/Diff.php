<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\UserInvitation;
use App\Exceptions\InvitedUserInvitedException;
use App\Exceptions\InvitedUserMemberedException;
use App\Exceptions\InvalidAccessException;
use App\Models\DiffLock;
use App\Models\Member;
use App\Events\DiffLocked;
use App\Events\DiffUnlocked;
use App\Events\DiffAddedMember;
use App\Events\DiffRemovedMember;
use App\Enums\Authority;

class Diff extends Model
{
    use HasFactory;

    protected $fillable = ['source_text', 'compared_text', 'title'];
    protected $appends = ['members_count'];

    public function getMembersCountAttribute()
    {
        return $this->members()->count();
    }

    public function locked()
    {
        return $this->hasOne(DiffLock::class, 'diff_id');
    }

    public function members(){
        return $this->hasMany(Member::class);
    }

    /**
     * 該当するUserのMemberを探します。
     */
    public function findMemberByUser(User $user)
    {
        return $this->members()->where('user_id', '=', $user->id);
    }

    public function addMember(User $user, int $authority = Authority::ADMIN): ?Member
    {
        $member = new Member([
            'diff_id' => $this->id,
            'user_id' => $user->id,
            'authority' => new Authority($authority)
        ]);
        $result = $member->save() ? $member : null;
        if($result){
            DiffAddedMember::dispatch($member);
        }
        return $result;
    }

    public function deleteMember(User $user): bool
    {
        $member = $this->findMemberByUser($user)->firstOrFail();
        $result =  isset($member) && $member->delete();

        if($result){
            DiffRemovedMember::dispatch($member);
        }
        return $result;
    }

    /**
     * 発行した招待
     */
    public function invitations()
    {
        return $this->hasMany(UserInvitation::class, 'diff_id');
    }

    /**
     * 招待を発行する
     */
    public function invite(User $user, User $partner)
    {
        $member = $this->findMemberByUser($user)->first();
        if(!isset($member)){
            \Log::debug('メンバーではありません');
            throw new InvitedUserMemberedException();
        }

        $invited = $this->invitations()->sharedLock()->where('invited_partner_id', $partner->id)->first();
        if($invited !== null){
            throw new InvitedUserInvitedException();
        }

        return UserInvitation::create([
            'invited_partner_id' => $partner->id,
            'author_id' => $member->user_id,
            'diff_id' => $this->id
        ]);

    }

    /**
     * Diffをロックする
     */
    public function lock(User $user): bool
    {
        $member = Member::where('diff_id', $this->id)->where('user_id', $user->id)->first();

        if($member === null){
            throw new InvalidAccessException();
        }

        $locked = $this->locked()->first();
        if(isset($locked)){
            return false;
        }

        $diffLocked = $this->locked()->create(['member_id' => $member->id]);
        DiffLocked::dispatch($diffLocked);

        return true;

    }

    /**
     * Diffのロックを解除する
     */
    public function unlock(User $user): bool
    {
        $member = Member::where('diff_id', $this->id)->where('user_id', $user->id)->first();

        if($member === null){
            throw new InvalidAccessException();
        }

        $locked = $this->locked()->first();
        if(isset($locked) && $locked->member()->first()->user_id === $user->id){
            $this->locked()->delete();
            DiffUnlocked::dispatch($this);
            return true;

        }

        return false;

        
    }

    /**
     * ユーザーによってロックされているか
     */
    public function isLockedByUser(User $user): bool
    {
        $locked = $this->locked()->first();
        return isset($locked) && $locked->user()->first()->id !== $user->id;
    }
}
