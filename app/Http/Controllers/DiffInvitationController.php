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

    public function search(Request $request, $diffId)
    {
        $diff = Auth::user()->diffs()->findOrFail($diffId);
        $columns = [
            'is_member' => function($query) use ($diff){
                $query->selectRaw('count(*)')
                    ->from('members')
                    ->whereRaw('users.id = members.user_id')
                    ->where('members.diff_id', '=', $diff->id);
            },
            'is_invited' => function($query) use ($diff){
                $query->selectRaw('count(*)')
                    ->from('user_invitations')
                    ->whereRaw('users.id = user_invitations.invited_partner_id')
                    ->where('user_invitations.diff_id', '=', $diff->id);
            },
            'users.*'
        ];
        $query = null;
        if($request->input('user_name', null) === null){
            $query = User::whereIn('users.id', UserInvitation::where('diff_id', '=', $diff->id)->select('invited_partner_id'));
        }else{
            $userName = \str_replace('%', '\%', $request->input('user_name'));
            $query = User::where('user_name', 'like', "%{$userName}%");

        }
        $page = $query->select($columns)->paginate()->appends(['user_name' => $request->input('user_name')]);

        return Inertia::render('Diff/Invitations', [
            'users' => $page,
            'diff' => $diff,
            'user_name' => $request->input('user_name')
        ]);
    }

    /**
     * 発行した招待を取り下げます
     */
    public function delete($diffId, $userId)
    {
        $me = Auth::user();
        $diff = $me->diffs()->findOrFail($diffId);
        $diff->invitations()->lockForUpdate()->where('invited_partner_id','=', $userId)->delete();
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
            \Log::debug($e);
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
