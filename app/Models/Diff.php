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

class Diff extends Model
{
    use HasFactory;

    protected $fillable = ['source_text', 'compared_text', 'title'];

    

    public function locked()
    {
        return $this->hasOne(DiffLock::class, 'diff_id');
    }

    public function members(){
        return $this->belongsToMany(User::class, 'members', 'diff_id', 'user_id');
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
        $user = $this->members()->findOrFail($user->id);
        $isMember = $this->members()->sharedLock()->find($partner->id) !== null;
        if($isMember){
            throw new InvitedUserMemberedException();
        }

        $invited = $this->invitations()->sharedLock()->where('invited_partner_id', $partner->id)->first();
        if($invited !== null){
            throw new InvitedUserInvitedException();
        }

        return UserInvitation::create([
            'invited_partner_id' => $partner->id,
            'author_id' => $user->id,
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
}
