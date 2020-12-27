<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Diff;
use App\Models\UserInvitation;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class DiffInvitationController extends Controller
{
    
    /**
     * 発行した招待を一覧表示します
     */
    public function invitations($diffId)
    {
        $diff = Auth::user()->diffs()->findOrFail($diffId);
        $invitations = $diff->invitations()->with(['invitedPartnerUser', 'author'])->paginate();
        return Inertia::render('Diff/Invitations', [
            'invitations' => $invitations,
            'diff' => $diff
        ]);
    }

    /**
     * 発行した招待を取り下げます
     */
    public function delete($diffId, $invitationId)
    {
        $me = Auth::user();
        $diff = $me->diffs()->findOrFail($diffId);
        $diff->invitations()->lockForUpdate()->findOrFail($invitationId)->delete();
        return Redirect::back()->with('success', '招待を取り下げました');
    }

    /**
     * ユーザーを招待します
     * @param $diffId 招待するDiff
     * @param $userId 招待するユーザー
     */
    public function create($diffId, $userId)
    {
        $me = Auth::user();
        $diff = $me->diffs()->findOrFail($diffId);
        $user = User::findOrFail($userId);
        \DB::beginTransaction();
        try{
            $diff->invite($me, $user);
        }catch(\Exception $e){
            \DB::rollback();
            return Redirect::back()->with('error', '無効な招待です。');
        }
    
        \DB::commit();

        return Redirect::back()->with('success', '招待を作成しました。');
        
    }

    public function new(Request $request, $diffId)
    {
        $diff = Auth::user()->diffs()->findOrFail($diffId);
        $users = User::notDiffMembers($diff)->notDiffInvitedUsers($diff)
            ->when($request->input('user_name_search') ?? null, function($query, $userNameSearch){
                $userNameSearch = str_replace('%', '\%', $userNameSearch);
                $query->where('user_name', 'like', "%{$userNameSearch}%");
            })->distinct('users.id')->paginate();

        return Inertia::render('Invitation/Edit',[
            'diff' => $diff,
            'users' => $users,
            'user_name_search' => $request->input('user_name_search')
        ]);
    }
}
