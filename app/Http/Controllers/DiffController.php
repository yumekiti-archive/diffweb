<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diff;
use App\Http\Requests\CreateDiffRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Events\DiffUpdated;
use Session;
use Illuminate\Support\Str;
use App\Http\Requests\UpdateDiffRequest;
use DB;


class DiffController extends Controller
{
    
    /**
     * Pages/Diff/Index.vueを返すようにしてください。
     * paginationを使用してページングできるようにしてください。　
     */
    public function index()
    {
        // 現在ログイン中のユーザー
        $user = Auth::user();
        // ユーザーがアクセス可能なDiffをすべて取得
        $diffs = $user->diffs()->orderBy("updated_at", "desc")->paginate();

        return Inertia::render('Diff/Index', [
        'diffs' => $diffs,
        ]);
    }

    /**
     * Pages/Diff/Edit.vueを返すようにしてください。
     */
    public function show($diffId)
    {
        $diff = Diff::with('locked.user')->findOrFail($diffId);

        
        return Inertia::render('Diff/Edit', [ 'diff' => $diff, 'me' => Auth::user(), 'client_id' => Str::uuid()]);

    }

    public function new()
    {
        return Inertia::render('Diff/Edit', ['me' => Auth::user() ]);

    }

    /**
     * Diffを作成します。
     */
    public function create(CreateDiffRequest $request)
    {
        $me = Auth::user();
        $diff = \DB::transaction(function() use ($request, $me){
            $diff = Diff::create($request->only(['title', 'source_text', 'compared_text']));

            $diff->addMember($me);

            return $diff;
        });

        return redirect()->route('diffs.show', ['diffId' => $diff->id ])->with('success', '作成に成功しました');
    }

    /**
     * Diffを更新ます。
     */
    public function update(UpdateDiffRequest $request, $diffId)
    {

        $diff = \DB::transaction(function () use ($request, $diffId){
            $me = Auth::user();
            $diff = Diff::where('id', $diffId)->lockForUpdate()->firstOrFail();
            if($diff->isLockedByUser($me)){
                return Redirect::back()->with('error', '他のユーザーによってロックされています。');
            }
            $diff->update($request->only('title', 'source_text', 'compared_text'));
            
            return $diff;
        });
        DiffUpdated::dispatch($diff, $request->input('client_id'));
        
        return Redirect::back();
    }

    /**
     * Diffを保存します。
     */
    public function save(UpdateDiffRequest $request, $diffId){
        $diff = DB::transaction(function() use($request, $diffId){
            $me = Auth::user();

            $diff = Diff::where('id', $diffId)->lockForUpdate()->firstOrFail();
            if($diff->isLockedByUser($me)){
                return Redirect::back()->with('error', '他のユーザーによってロックされています。');
            }
            $diff->update($request->only('title', 'source_text', 'compared_text'));
            if($request->input('unlock') == true && $diff->members_count > 1){
                $diff->unlock($me);
            }
            return $diff;
        });
        DiffUpdated::dispatch($diff, $request->input('client_id'));

        
        return Redirect::back()->with('success', '保存に成功しました。');

    }


    /**
     * Diffを編集ロックします。
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
