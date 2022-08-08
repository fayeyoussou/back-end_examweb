<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProduitController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

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


Route::controller(UserController::class)->group(function(){
    // Route::post('register', 'register')->middleware('auth:sanctum')->name('register');
    Route::post('login', 'login')->name('login');
    Route::post('logout','logout')->middleware('auth:sanctum')->name('logout');

});

Route::middleware('auth:sanctum')->group( function () {
    Route::resource('categorie', CategorieController::class);
    Route::resource('produit', ProduitController::class);
    Route::resource('role', RoleController::class);
    Route::resource('user',UserController::class);

});

//Illuminate\Database\QueryException: SQLSTATE[42S22]: Column not found: 1054 Unknown column 'expires_at' in 'field list' (SQL: insert into `personal_access_tokens` (`name`, `token`, `abilities`, `expires_at`, `tokenable_id`, `tokenable_type`, `updated_at`, `created_at`) values (MyApp, 1179e06dfc88619abe7bba02c7d93b1f687e57f3502bdc6547c00e4db9ebc886, ["*"], ?, 1, App\Models\User, 2022-08-01 11:59:14, 2022-08-01 11:59:14)) in file /Users/youssouphafaye/Desktop/laravel/apitest/vendor/laravel/framework/src/Illuminate/Database/Connection.php on line 759
