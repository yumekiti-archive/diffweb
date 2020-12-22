<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DiffInvitationController extends Controller
{
    
    /**
     * 発行した招待を一覧表示します
     */
    public function invitations($diffId)
    {

    }

    /**
     * 発行した招待を取り下げます
     */
    public function cancel($diffId, $invitationId)
    {

    }

    /**
     * ユーザーを招待します
     * @param $diffId 招待するDiff
     * @param $userId 招待するユーザー
     */
    public function invite($diffId, $userId)
    {

    }
}
