<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    // 商品検索処理後、一覧画面に戻る
    Route::match(['get', 'post'], '/homeProduct', [App\Http\Controllers\TestController::class, 'index'])->name('index');
    // 商品新規登録画面
    Route::get('/createProduct', [App\Http\Controllers\TestController::class, 'create'])->name('create');
    // 商品新規登録処理
    Route::post('/createProduct', [App\Http\Controllers\TestController::class, 'store'])->name('store');
    // IDを取得して詳細画面を表示 
    Route::get('/detailProduct/{id}', [App\Http\Controllers\TestController::class, 'detail'])->name('detail');
    // IDを取得して編集画面を表示
    Route::get('/editProduct/{id}', [App\Http\Controllers\TestController::class, 'edit'])->name('edit');
    // IDを取得して商品情報を更新する
    Route::put('/editProduct/{id}', [App\Http\Controllers\TestController::class, 'update'])->name('update');
    // 一覧画面削除処理（IDの取得しないとエラーになるが削除にも必要？）
    Route::delete('/deleteProduct/{id}', [App\Http\Controllers\TestController::class, 'delete'])->name('delete');
});
