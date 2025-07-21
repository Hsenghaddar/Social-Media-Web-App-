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

    // Like routes
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');

    //friends routes
    Route::post("/add/friend/{id}", [FriendsController::class, 'sendFriendRequest'])->name('Friend.add');
    Route::post("/remove/friend/{id}", [FriendsController::class, 'removeFriendRequest'])->name('Friend.remove');
    Route::post("/accept/friend/{id}", [FriendsController::class, 'acceptFriendRequest'])->name('Friend.accept');
    Route::post("/reject/friend/{id}", [FriendsController::class, 'declineFriendRequest'])->name('Friend.decline');

    //privacy routes
    Route::post("/change/privacy",[AuthController::class,"changePrivacy"])->name("User.privacy");
    
});
Route::resource('posts', PostController::class)->only(['index', 'show']);

//Route by a specific user, by  a general condition
//Specific Action, on a Specific Object, by a Specific User
