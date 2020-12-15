<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Diff;
use App\Models\Member;

class UserInvitation extends Model
{
    use HasFactory;

    /**
     * 招待した相手
     */
    public function invitedOpponentUser()
    {
        return $this->belongsTo(User::class, 'invited_partner_id');
    }

    /**
     * 招待したユーザー
     */
    public function invitedUser()
    {
        // 正しく動くか怪しい
        return $this->hasOneThrought(
            User::class,
            Member::class, // 自分を参照する中間テーブル
            'id', // member.id 
            'id', // 取得テーブルが参照する中間テーブルに対する参照キー
            'invited_member_id',
            'invited_user_id' 

        );

    }

    /**
     * 招待先のDiffを取得する
     */
    public function diff(){

        // 正しく動くか怪しい
        return $this->hasOneThrought(
            Diff::class,
            Member::class,
            'id',
            'id',  // Diffの外部キー
            'invited_member_id', // 
            'diff_id' // diffsのローカルキー
        );
    }
}
