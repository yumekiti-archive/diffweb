<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diff;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Gate;

class MemberController extends Controller
{
    
    /**
     * メンバーを一覧表示します
     */
    public function index($diffId)
    {

        $diff = Diff::findOrFail($diffId);
        Gate::authorize('diff-admin', $diff);

        $members = $diff->members()->with(['user'])->paginate();

        return Inertia::render('Diff/Members', [
            'members' => $members,
            'diff' => $diff,
            'member' => $diff->findMemberByUser(Auth::user())->firstOrFail()
        ]);

    }

    /**
     * メンバーを除名します$request->input('password')を検証してください。
     */
    public function destroy(Request $request, $diffId, $userId)
    {
        $diff = Diff::findOrFail($diffId);
        Gate::authorize('diff-admin', $diff);

        $me = Auth::user();
        if(Hash::check($request->input('password'), $me->password)){
            $user = User::findOrFail($userId);
            $result = $diff->deleteMember($user);
            if($result){
                if($user->id == $me->id){
                    return Redirect::route('diffs')->with('success', 'メンバーから抜けました。');
                }
                
                return Redirect::back()->with('success', 'メンバーを除外しました。');
            }else{
                return Redirect::back()->with('error', 'メンバーの除外に失敗しました。');
            }
            

        }
        return Redirect::back()->with('error', 'パスワードを正しく入力してください。');

    }

    public function changeAuthority(Request $request, $diffId, $userId)
    {
        $diff = Auth::user()->diffs()->findOrFail($diffId);
        Gate::authorize('diff-admin', $diff);
        $member = $diff->findMemberByUser(User::findOrFail($userId));
        $member->authority = $request->input('authority');
        if($member->save()){
            return Redirect::back()->with('success', '権限の変更に成功しました。');
        }else{
            return Redirect::back()->with('error', '権限の変更に失敗しました。');
        }
    }
}
