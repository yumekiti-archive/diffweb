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
        $users = $diff->members()->paginate();

        return Inertia::render('Diff/Members', [
            'users' => $users,
            'diff' => $diff,
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
            $user = $diff->members()->findOrFail($userId);
            $diff->deleteMember($user);
            if($user->id == $me->id){
                return Redirect::route('diffs')->with('success', 'メンバーから抜けました。');
            }
            
            return Redirect::back()->with('success', 'メンバーを除外しました。');

        }
        return Redirect::back()->with('error', 'パスワードを正しく入力してください。');

    }
}
