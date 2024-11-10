<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PaymentController;

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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [ProfileController::class, 'mypage'])->name('mypage');
    Route::get('/mypage/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::patch('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/sell', [ItemController::class, 'sell'])->name('sell');
    Route::post('/sell', [ItemController::class, 'store'])->name('sell.store');
    Route::get('/purchase/{item_id}', [PaymentController::class, 'purchase'])->name('purchase');
    Route::post('/purchase/{item_id}', [PaymentController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/{item_id}/success', [PaymentController::class, 'success'])->name('purchase.success');
    Route::get('/purchase/address/{item_id}', [PaymentController::class, 'address'])->name('address');
    Route::patch('/purchase/address/{item_id}', [PaymentController::class, 'update'])->name('address.update');
    Route::post('/item/{item_id}/favorites', [FavoriteController::class, 'create'])->name('favorite.create');
    Route::delete('/item/{item_id}/favorites', [FavoriteController::class, 'delete'])->name('favorite.delete');
    Route::post('/items/{item}/comments', [CommentController::class, 'store'])->name('comments.store');
});

Route::post('/login', [AuthController::class, 'store']);
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/', [ItemController::class, 'index'])->name('index');
Route::get('/item/{item_id}', [ItemController::class, 'item'])->name('item');

Route::get('/email/verify', function () {
    return view('verify');
})->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/mypage/profile');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/resend-verification-email', [VerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.resend');