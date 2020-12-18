<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diff;
use App\Http\Requests\EditDiff;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;



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

        return Inertia::render('Diff/Edit', [ 'diff' => $diff]);

    }

    public function new()
    {
        return Inertia::render('Diff/Edit');

    }

    /**
     * Diffを作成します。
     */
    public function create(EditDiff $request)
    {
        $me = Auth::attempt(['email' => $email, 'password' => $password]);
        $diff = \DB::transaction(function() use ($request, $me){
            $diff = Diff::create($request->only(['title', 'source_text', 'compated_text']));

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

    }

    /**
     * Diffをロックします。
     */
    public function lock($diffId)
    {

    }

    /**
     * Diffのロックを解除します。
     */
    public function unlock($diffId)
    {

    }

   

}
