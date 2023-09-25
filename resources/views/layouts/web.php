<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Facebook\PostController;
use App\Http\Controllers\Facebook\UserController;
use App\Http\Controllers\Facebook\FbRuleController;
use App\Http\Controllers\Facebook\CommentController;
use App\Http\Controllers\Facebook\CategoryController;
use App\Http\Controllers\Facebook\FacebookController;
use App\Http\Controllers\Facebook\MerchantController;
use App\Http\Controllers\Facebook\AutoReplyController;



Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::prefix('admin')->group(function () {
    # User
    Route::get('user', [UserController::class,'index'])->name('user.index');
    Route::get('user/page', [UserController::class,'getPage'])->name('user.page');
    Route::post('user/add', [UserController::class,'add'])->name('user.add');

    # Rule.
    Route::get('rule', [FbRuleController::class,'getRule'])->name('rule.index');



    # Facebook
    Route::resource('facebook', FacebookController::class);
    Route::get('auth/facebook', [FacebookController::class,'redirectToFacebook'])->name('facebook.redirect');
    Route::get('auth/facebook/callback', [FacebookController::class,'handleFacebookCallback'])->name('facebook.callback');

    Route::post('facebook/page', [FacebookController::class, 'createPost'])->name('facebook.page');
    Route::get('auth/facebook/post', [FacebookController::class, 'getPost'])->name('facebook.post');
    // Route::post('auth/facebook/upload', [FacebookController::class, 'postUpload'])->name('facebook.upload');

    Route::resource('post', PostController::class);
    Route::resource('categories', CategoryController::class);

    Route::post('comments', [CommentController::class,'addComments'])->name('comments.add');
    Route::get('comments/{id}', [CommentController::class,'getKeyword']);

    Route::resource('auto-reply', AutoReplyController::class);
});


