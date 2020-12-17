<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvitationController extends Controller
{
    
    /**
     * Diffの招待を一覧表示します。
     */
    public function diffsInvitations($diffId)
    {

    }

    /**
     * $user->input('user_name')へ招待を発行します。
     */
    public function create(Request $user, $diffId)
    {

    }


    /**
     * 招待を取り下げます
     */
    public function cancelInvitation($diffId, $invitationId)
    {

    }

    /**
     * 自分への招待を一覧表示します。
     */
    public function invitations()
    {

    }

    /**
     * 招待を受け入れます。
     */
    public function accept($invitationId)
    {

    }

    /**
     * 招待を拒否します
     */
    public function reject($invitationId)
    {

    }
    
}
