<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendsController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Home page
Route::get('/', [PostController::class, 'index'])->name('home');

// Authentication routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Post routes (public)
Route::get('/profile/{user}', [PostController::class, 'profile'])->name('profile');
// Post routes (protected)
Route::middleware('auth')->group(function () {
    Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::get("/posts/friends",[PostController::class,"friendsPosts"])->name("posts.friends");
    Route::post('posts', [PostController::class, 'store'])->name('posts.store');
    
    Route::get('posts/{post}/edit', [PostController::class, 'edit'])
        ->middleware('can:update,post')->name('posts.edit');
    Route::put('posts/{post}', [PostController::class, 'update'])
        ->middleware('can:update,post')->name('posts.update');
    Route::delete('posts/{post}', [PostController::class, 'destroy'])
        ->middleware('can:delete,post')->name('posts.destroy');

    // Comment routes
    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])
        ->middleware('can:delete,comment')->name('comments.destroy');

    //privacy routes
    Route::post("/change/privacy",[AuthController::class,"changePrivacy"])->name("User.privacy");
    
});
Route::get("/posts/{post}",[PostController::class,"show"])->middleware("can:view,post")->name("posts.show");
Route::resource('posts', PostController::class)->only(['index']);

//Route by a specific user, by  a general condition
//Specific Action, on a Specific Object, by a Specific User
