<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/create', [HomeController::class, 'create'])->name('create');
Route::post('/store', [HomeController::class, 'store'])->name('store');
Route::get('/edit/{id}', [HomeController::class, 'edit'])->name('edit');
Route::post('/update', [HomeController::class, 'update'])->name('update');
Route::post('/update_checklist', [HomeController::class, 'update_checklist'])->name('update_checklist');
Route::get('/detail/{id}', [HomeController::class, 'detail'])->name('detail');
Route::post('/destroy', [HomeController::class, 'destroy'])->name('destroy');
Route::post('/category_destroy', [HomeController::class, 'category_destroy'])->name('category_destroy');
Route::get('/quiz_all', [HomeController::class, 'quiz_all'])->name('quiz_all');
Route::get('/quiz_checked', [HomeController::class, 'quiz_checked'])->name('quiz_checked');
Route::get('/quiz_result', [HomeController::class, 'quiz_result'])->name('quiz_result');
Route::post('/result', [HomeController::class, 'result'])->name('result');
Route::get('/category', [HomeController::class, 'category'])->name('category');
Route::get('/group', [HomeController::class, 'group'])->name('group');

// グループユーザーの投稿を閲覧
Route::get('/friends_phrases/{id}', [HomeController::class, 'friends_phrases'])->name('friends_phrases');
//「招待メール送信フォーム画面表示」処理
Route::get('/invite', [HomeController::class, 'invite'])->name('invite');
//「メール確認画面表示」処理
Route::post('/invitation_confirm',[HomeController::class, 'invitation_confirm'])->name('invitation_confirm');

//「家族招待メール送信」処理
Route::get('/invite_email', [HomeController::class, 'invite_email'])->name('invite_email');
//「招待用の登録フォーム画面表示」処理
Route::get('/invited/{token}', [HomeController::class, 'invited_token'])->name('invited_token');
//「招待用のユーザー登録」処理
Route::post('/invited', [HomeController::class, 'invited'])->name('invited');



Auth::routes();

