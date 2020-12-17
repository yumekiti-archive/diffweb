<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diff;
use App\Http\Requests\EditDiff;

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
        
    }

    /**
     * Diffを作成します。
     */
    public function create(EditDiff $request)
    {

    }

    /**
     * Diffを更新します。
     */
    public function update(EditDiff $request, $diffId)
    {

    }

    public function lock($diffId)
    {

    }

    public function unlock($diffId)
    {

    }

   

}
