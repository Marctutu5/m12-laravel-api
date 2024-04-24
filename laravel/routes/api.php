<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\PlaceController;
use App\Http\Controllers\Api\VisibilityController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ReviewController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('guest')->group(function () {
    // Token
    Route::post('register', [TokenController::class, 'register']);
    Route::post('login', [TokenController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    // Token
    Route::get('user', [TokenController::class, 'user']);
    Route::post('logout', [TokenController::class, 'logout']);
    
    // Wallet Routes
    Route::get('wallet', [WalletController::class, 'show']);
    Route::post('wallet/update', [WalletController::class, 'update']);
    
    
    
    // OLD ROUTES
    // Files
    Route::apiResource('files', FileController::class);
    Route::post('files/{file}', [FileController::class, 'update_workaround'])
        ->name('files.update_file');
    // Posts
    Route::apiResource('posts', PostController::class);
    Route::controller(PostController::class)->group(function () {
        Route::post('posts/{post}', 'update_workaround')
            ->name('posts.update_post');
        Route::post('posts/{post}/likes', 'like')
            ->name('posts.like');
        Route::delete('posts/{post}/likes', 'unlike')
            ->name('posts.unlike');
    });
    Route::apiResource('posts.comments', CommentController::class);
    // Places
    Route::apiResource('places', PlaceController::class);
    Route::controller(PlaceController::class)->group(function () {
        Route::post('places/{place}', 'update_workaround')
            ->name('places.update_post');
        Route::post('places/{place}/favorites', 'favorite')
            ->name('places.favorite');
        Route::delete('places/{place}/favorites', 'unfavorite')
            ->name('places.unfavorite');
    });
    Route::apiResource('places.reviews', ReviewController::class);
    // Visibilites
    Route::apiResource('visibilities', VisibilityController::class);
});
