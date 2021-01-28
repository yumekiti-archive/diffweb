<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Diff;
use App\Models\UserInvitation;
use BenSampo\Enum\Traits\CastsEnums;
use App\Enums\Authority;
use BenSampo\Enum\Rules\EnumKey;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'diff_id', 'authority'
    ];

    protected $casts = [
        'authority' => Authority::class
    ];


    public function rules() 
    {
        return [
            'authority' => new EnumKey(Authority::class)
        ];
    }
    /**
     * dbのカラム user_id references id on users;
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * dbのカラム diff_id references id on diffs;
     */
    public function diff()
    {
        return $this->belongsTo(Diff::class, 'diff_id');
    }

    /**
     * 発行した招待
     */
    public function invitations()
    {
        return $this->hasMany(UserInvitation::class, 'invited_member_id');
    }
}
