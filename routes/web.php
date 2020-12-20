<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiffController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/welcome', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');


Route::group(['middlware' => ['auth:sanctum']], function () {
    
    // アクセス可能なDiffを一覧表示します
    Route::get('/', [DiffController::class, 'index'])->name('diffs');

    // Diffを新規作成します。
    Route::post('/diffs', [DiffController::class, 'create'])->name('diffs.create');
    Route::get('/create', [DiffController::class, 'new'])->name('diffs.new');


    Route::group(['middleware' => ['member']], function () {
        // アクセス可能なDiffをDiffのIdで取得しDiffを画面に表示します
        // 現在編集モードで編集しているユーザーがいたらそれを表示してください。
        // 他のユーザーが編集している時は編集できないようにしてください。
        Route::get('/diffs/{diffId}', [DiffController::class, 'show'])->name('diffs.show');

        // Diffを更新します
        // 他のユーザーが編集しているにも関わらずputしようとしている場合はエラーを返してください。
        Route::put('/diffs/{diffId}', [DiffController::class, 'update'])->name('diffs.update');

        // Diffを編集モードにします。
        // 先客がいればエラーを返してください。
        Route::put('/diffs/{diffId}/lock', [DiffController::class, 'lock'])->name('diffs.lock');

        // Diffの編集モードを解除します。
        // 編集モードを解除しているユーザーと編集中のユーザーが一致しているかチェックしてから解除してください。
        Route::delete('/diffs/{diffId}/lock', [DiffController::class, 'unlock'])->name('diffs.unlock');

        // Diffにアクセスすることのできるメンバーを一覧表示します。
        Route::get('/diffs/{diffId}/users', [MemberController::class, 'index'])->name('diffs.members.index');

        // 一定期間内でパスワードを確認済みであればメンバーを削除します
        // リクエストパラメーターにパスワードを含めてそのパスワードを検証します。
        Route::delete('/diffs/{diffId}/users/{userId}', [MemberController::class, 'destroy'])->name('diffs.members.destroy');

        // diffへの招待を一覧表示します。
        Route::get('/diffs/{diffId}/invitations', [InvitationController::class, 'diffsInvitations'])->name('diffs.invitations');

        // ユーザーを招待します。
        // user_idをpostパラメーターに含めるようにしてください。
        Route::post('/diffs/{diffId}/invitations', [InvitationController::class, 'create'])->name('diffs.invitations.create');

        // ユーザーへの招待を取り下げます。
        Route::delete('/diffs/{diffId}/invitations/{invitationId}', [InvitationController::class, 'cancelInvitation'])->name('diffs.invitations.cancel');
        


    });

    // 自分への招待を一覧表示します。
    // またDiffのタイトルとDiffのIdを含めるようにしてください。
    Route::get('/invitations', [InvitationController::class, 'invitations'])->name('invitations');

    // 招待をacceptします。
    Route::post('/invitations/{invitationId}/accept', [InvitationController::class, 'accept']);
    
    // 招待を拒否します。
    Route::post('/invitations/{invitationId}/reject', [InvitationController::class, 'reject']);


    
});

// user_nameでユーザーを検索します。
Route::get('/users/{user_name?}', [UserController::class, 'search'])->name('users.search');

