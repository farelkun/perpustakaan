<?php

use App\Http\Controllers\Api\Master\BookCategoryController;
use App\Http\Controllers\Api\Master\BookController;
use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\RoleController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\Master\CustomerController;
use App\Http\Controllers\Api\Master\ItemController;
use App\Http\Controllers\Api\Transaction\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    /**
     * CRUD user
     */
    Route::get('/users', [UserController::class, 'index'])->middleware(['web', 'auth.api:user_view']);
    Route::get('/users/{id}', [UserController::class, 'show'])->middleware(['web', 'auth.api:user_view']);
    Route::post('/users', [UserController::class, 'store'])->middleware(['web', 'auth.api:user_create']);
    Route::put('/users', [UserController::class, 'update'])->middleware(['web', 'auth.api:user_update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware(['web', 'auth.api:user_delete']);

    /**
     * CRUD role / hak akses
     */
    Route::get('/roles', [RoleController::class, 'index'])->middleware(['web', 'auth.api:roles_view']);
    Route::get('/roles/{id}', [RoleController::class, 'show'])->middleware(['web', 'auth.api:roles_view']);
    Route::post('/roles', [RoleController::class, 'store'])->middleware(['web', 'auth.api:roles_create']);
    Route::put('/roles', [RoleController::class, 'update'])->middleware(['web', 'auth.api:roles_update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->middleware(['web', 'auth.api:roles_delete']);

     /**
     * CRUD book categories
     */
    Route::get('/book-categories', [BookCategoryController::class, 'index'])->middleware(['web', 'auth.api:book_category_view']);
    Route::get('/book-categories/{id}', [BookCategoryController::class, 'show'])->middleware(['web', 'auth.api:book_category_view']);
    Route::post('/book-categories', [BookCategoryController::class, 'store'])->middleware(['web', 'auth.api:book_category_create']);
    Route::put('/book-categories', [BookCategoryController::class, 'update'])->middleware(['web', 'auth.api:book_category_update']);
    Route::delete('/book-categories/{id}', [BookCategoryController::class, 'destroy'])->middleware(['web', 'auth.api:book_category_delete']);

     /**
     * CRUD books
     */
    Route::get('/books', [BookController::class, 'index'])->middleware(['web', 'auth.api:book_view']);
    Route::get('/books/{id}', [BookController::class, 'show'])->middleware(['web', 'auth.api:book_view']);
    Route::post('/books', [BookController::class, 'store'])->middleware(['web', 'auth.api:book_create']);
    Route::put('/books', [BookController::class, 'update'])->middleware(['web', 'auth.api:book_update']);
    Route::delete('/books/{id}', [BookController::class, 'destroy'])->middleware(['web', 'auth.api:book_delete']);

     /**
     * CRUD books
     */
    Route::get('/transactions', [TransactionController::class, 'index'])->middleware(['web', 'auth.api:transaction_view']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->middleware(['web', 'auth.api:transaction_view']);
    Route::post('/transactions', [TransactionController::class, 'store'])->middleware(['web', 'auth.api:transaction_create']);
    Route::put('/transactions', [TransactionController::class, 'update'])->middleware(['web', 'auth.api:transaction_update']);
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->middleware(['web', 'auth.api:transaction_delete']);

    /**
     * Route khusus authentifikasi
     */
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile'])->middleware(['auth.api']);
        Route::get('/csrf', [AuthController::class, 'csrf'])->middleware(['web']);
    });
});

Route::get('/', function () {
    return response()->failed(['Endpoint yang anda minta tidak tersedia']);
});

/**
 * Jika Frontend meminta request endpoint API yang tidak terdaftar
 * maka akan menampilkan HTTP 404
 */
Route::fallback(function () {
    return response()->failed(['Endpoint yang anda minta tidak tersedia']);
});
