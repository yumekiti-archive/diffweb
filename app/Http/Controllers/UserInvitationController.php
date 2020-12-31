<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class UserInvitationController extends Controller
{
    
    /**
     * 自分への招待を一覧表示します
     */
    public function invitations()
    {
        $invitations = Auth::user()->invitationsToMe()->with('diff', 'author')->paginate();
        return Inertia::render('Invitation/Index', [
            'invitations' => $invitaitons
        ]);
    }

    /**
     * 招待を受け入れます
     */
    public function accept($invitationId)
    {
        $me = Auth::me();
        return \DB::transaction(function() use ($me, $invitationId){
            $invitation = $me->invitationsToMe()->lockForUpdate()->findOrFail($invitationId);

            $invitations->accept();

            return Redirect::back()->with('success', '招待を受け入れました。');
        });
    }

    /**
     * 招待を拒否します。
     */
    public function reject($invitationId)
    {
        
        $me = Auth::me();
        return \DB::transaction(function() use ($me, $invitationId){
            $me->invitationsToMe()->lockForUpdate()->findOrFail($invitationId)->reject();

            return Redirect::back()->with('success', '招待を拒否しました。');
        });
    }
}
