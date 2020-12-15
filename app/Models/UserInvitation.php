<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInvitation extends Model
{
    use HasFactory;

    /**
     * 招待した相手
     */
    public function invitedOpponentUser()
    {

    }

    /**
     * 招待したユーザー
     */
    public function invitedUser()
    {

    }

    /**
     * 招待先のDiffを取得する
     */
    public function diff(){
        
    }
}
