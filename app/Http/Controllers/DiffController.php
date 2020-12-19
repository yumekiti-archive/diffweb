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

        return redirect()->route('diffs.show', ['diffId' => $diff->id ]);
    }

    /**
     * Diffを更新します。
     */
    public function update(EditDiff $request, $diffId)
    {

        $me = Auth::user();
        $diff = Diff::where('id', $diffId)->lockForUpdate()->firstOrFail();
        $diff->update($request->only('title', 'source_text', 'compared_text'));
        return Redirect::back()->with('success', '保存に成功しました。');
    }

    /**
     * Diffをロックします。
     */
    public function lock($diffId)
    {
        $result = \DB::transaction(function() use($diffId){
            $diff = Diff::lockForUpdate()->findOrFail($diffId);
            $user = Auth::user();
            
            $locked = $diff->lockedUser()->first();
            if(isset($locked) && $locked->id === $user->id){
                return Redirect::back()->with('error', '他のユーザーによってロックされています。');
            }
            $diff->lockedUser()->associate($user);
            $diff->save();
            return $diff->load(['lockedUser']);
        });

        //return Inertia::render('Diff/Edit', ['diff' => $result, 'me' => Auth::user()]);
        return Redirect::back();
    }

    /**
     * Diffのロックを解除します。
     */
    public function unlock($diffId)
    {
        $result = \DB::transaction(function() use($diff){
            $diff = Diff::lockForUpdate()->findOrFail($diffId);
            $user = Auth::user();
            $locked = $diff->lockedUser()->first();
            if(isset($locked) && $locked->id === $user->id){
                $diff->lockedUser()->dissociate();
                $diff->save();
            }else if(isset($locked) && $locked !== $user->id){
                abort(403);
            }
            return $diff;
        });

        return Redirect::back();
    }

   

}
