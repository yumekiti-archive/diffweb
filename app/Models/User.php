<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Diff;
use App\Models\UserInvitation;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'email',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * アクセス権のあるDiffを取得する
     */
    public function diffs(){
        return $this->belongsToMany(Diff::class, 'members', 'user_id', 'diff_id');
    }

    /**
     * 自分への招待
     */
    public function invitationsToMe()
    {
        return $this->hasMany(UserInvitation::class, 'invited_partner_id');
    }

    public function scopeNotDiffMembers($query, Diff $diff)
    {
        
        return $query->whereDoesntHave('diffs', function($query) use ($diff){
            $query->where('diff_id', '=', $diff->id);
        });
    }

    public function scopeNotDiffInvitedUsers($query, Diff $diff)
    {
        return $query->whereDoesntHave('invitationsToMe', function($query) use ($diff){
            $query->where('diff_id', '=', $diff->id);
        });
    }
}
