<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diff;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class MemberController extends Controller
{
    
    /**
     * メンバーを一覧表示します
     */
    public function index($diffId)
    {
        $diff = Diff::findOrFail($diffId);
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
}
