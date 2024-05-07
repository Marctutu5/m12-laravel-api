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
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\BackpackController;
use App\Http\Controllers\Api\ListingController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserPositionController;
use App\Http\Controllers\Api\MapItemController;
use App\Http\Controllers\Api\UserCollectedItemController;
use App\Http\Controllers\Api\FissurialController;
use App\Http\Controllers\Api\AttackController;
use App\Http\Controllers\Api\FissurialAttackController;
use App\Http\Controllers\Api\UserFissurialController;


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
    
    // Wallet
    Route::get('wallet', [WalletController::class, 'show']);
    Route::post('wallet/update', [WalletController::class, 'update']);
    
    // Item
    Route::get('items', [ItemController::class, 'index']); // Ruta para obtener todos los items
    Route::get('items/{id}', [ItemController::class, 'show']); // Ruta para obtener un item específico
    
    // Backpack
    Route::get('backpacks', [BackpackController::class, 'index']);  // Para listar todas las backpacks
    Route::get('backpack', [BackpackController::class, 'show']);  // Para mostrar la backpack de un usuario específico
    Route::post('backpack/update', [BackpackController::class, 'update']); // Actualizar items de la backpack
    
    // Listings
    Route::get('listings', [ListingController::class, 'index']);  // Mostrar todos los listados
    Route::post('listings', [ListingController::class, 'store']);  // Crear un nuevo listado
    Route::get('listings/{id}', [ListingController::class, 'show']);  // Mostrar un listado específico
    Route::delete('listings/{id}', [ListingController::class, 'destroy']);  // Eliminar un listado específico

    // Transactions
    Route::get('transactions', [TransactionController::class, 'index']);  // Mostrar todas las transacciones
    Route::post('transactions', [TransactionController::class, 'store']);  // Registrar una nueva transacción

    // UserPosition
    Route::get('user-position', [UserPositionController::class, 'show']);  // Mostrar la posición del usuario autenticado
    Route::post('user-position', [UserPositionController::class, 'update']);  // Actualizar la posición del usuario autenticado

    Route::get('/mapitems', [MapItemController::class, 'index']);
    Route::get('/mapitems/{id}', [MapItemController::class, 'show']);
    Route::post('/mapitems/{id}', [MapItemController::class, 'update']);

    Route::get('/usercollecteditems', [UserCollectedItemController::class, 'index']);
    Route::post('/usercollecteditems', [UserCollectedItemController::class, 'store']);
    Route::get('/usercollecteditems/{id}', [UserCollectedItemController::class, 'show']);
    Route::post('/usercollecteditems/{id}', [UserCollectedItemController::class, 'update']);

    // Fissurials
    Route::get('/fissurials', [FissurialController::class, 'index']);
    Route::get('/fissurials/{id}', [FissurialController::class, 'show']);

    // Attacks
    Route::get('/attacks', [AttackController::class, 'index']);
    Route::get('/attacks/{id}', [AttackController::class, 'show']);

    // Fissurials Attacks
    Route::get('/fissurial-attacks', [FissurialAttackController::class, 'index']);
    Route::get('/fissurial-attacks/fissurial/{fissurial_id}', [FissurialAttackController::class, 'getByFissurial']);
    Route::get('/fissurial-attacks/attack/{attack_id}', [FissurialAttackController::class, 'getByAttack']);

    // Users Fissurials
    Route::get('/user-fissurials', [UserFissurialController::class, 'index']);
    Route::get('/user-fissurial', [UserFissurialController::class, 'show']);
    Route::post('/user-fissurial/update', [UserFissurialController::class, 'update']);

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
