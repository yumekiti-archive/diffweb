<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Diff;
use Illuminate\Support\Facades\Auth;

class CheckDiffMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check()){

            $user = Auth::user();
            $diff = Diff::findOrFail($request->route()->parameter('diffId'));
            if( ! $diff->findMemberByUser($user)->first()){
                // diffへアクセス権がない場合とりあえず401を返す
                \abort(401);
            }

        }
        
        return $next($request);
    }
}
