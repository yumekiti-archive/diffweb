<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Diff;
use App\Models\UserInvitation;

class Member extends Model
{
    use HasFactory;

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
