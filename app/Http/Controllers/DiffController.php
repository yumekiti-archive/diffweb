<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diff;
use App\Http\Requests\EditDiff;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;



class DiffController extends Controller
{
    
    /**
     * Pages/Diff/Index.vueを返すようにしてください。
     * paginationを使用してページングできるようにしてください。　
     */
    public function index()
    {
    }

    /**
     * Pages/Diff/Edit.vueを返すようにしてください。
     */
    public function show($diffId)
    {
        $diff = Diff::with('lockedUser')->findOrFail($diffId);

        return Inertia::render('Diff/Edit', [ 'diff' => $diff, 'me' => Auth::user() ]);

    }

    public function new()
    {
        return Inertia::render('Diff/Edit', ['me' => Auth::user() ]);

    }

    /**
     * Diffを作成します。
     */
    public function create(EditDiff $request)
    {
        $me = Auth::user();
        $diff = \DB::transaction(function() use ($request, $me){
            $diff = Diff::create($request->only(['title', 'source_text', 'compared_text']));

            $diff->members()->attach($me);

            return $diff;
        });

        return redirect()->route('diffs.show', ['diffId' => $diff->id ])->with('success', '作成に成功しました');
    }

    /**
     * Diffを更新します。
     */
    public function update(EditDiff $request, $diffId)
    {

        \DB::transaction(function () use ($request, $diffId){
            $me = Auth::user();
            $diff = Diff::where('id', $diffId)->lockForUpdate()->firstOrFail();
            $lockedUser = $diff->lockedUser()->first();
            if(isset($lockedUser) && $lockedUser->id !== $me->id){
                return Redirect::back()->with('error', '他のユーザーによってロックされています。');
            }
            $diff->update($request->only('title', 'source_text', 'compared_text'));
            $diff->lockedUser()->dissociate();
            $diff->save();
        });
        
        return Redirect::back()->with('success', '保存に成功しました。');
    }

    /**
     * Diffをロックします。
     */
    public function lock($diffId)
    {
        
        $user = Auth::user();
        return \DB::transaction(function() use (&$user, $diffId){
            $diff = $user->diffs()->lockForUpdate()->findOrFail($diffId);
            $result = $diff->lock($user);
            if($result){
                return Redirect::back()->with('success', 'ロックしました。');
            }else{
                return Redirect::back()->with('error', '他のユーザーによってロックされています。');
            }
        });

    }

    /**
     * Diffのロックを解除します。
     */
    public function unlock($diffId)
    {
        return \DB::transaction(function() use ($diffId){
            $user = Auth::user();
            $diff = $user->diffs()->lockForUpdate()->findOrFail($diffId);
            if($diff->unlock($user)){
                return Redirect::back()->with('success', 'ロックを解除しました。');
            }else{
                return Redirect::back()->with('error', '他のユーザーによってロックされています。');
            }
        });
    }

   

}
