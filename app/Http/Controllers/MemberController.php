<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemberController extends Controller
{
    
    /**
     * メンバーを一覧表示します
     */
    public function index()
    {

    }

    /**
     * メンバーを除名します$request->input('password')を検証してください。
     */
    public function destroy(Request $request, $diffId, $userId)
    {
        
    }
}
