<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\UsersListController;
use App\Http\Controllers\API\ShopController;
use App\Http\Controllers\Api\ItemsDashboardController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy buildingW your API!
|
*/
Route::get('/auth/verify/{token}', [AuthController::class, 'verifyUser']);
Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);
Route::post('/auth/sendPasswordReset', [AuthController::class, 'sendPasswordReset']);
Route::post('/auth/checkCode', [AuthController::class, 'checkCode']);
Route::post('/auth/resetPassword', [AuthController::class, 'resetPassword']);
Route::post('/addItem', [ItemController::class, 'store']);
Route::get('/retrieveMainCategories', [ShopController::class, 'retrieveMainCategories']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/getItems', [ItemController::class, 'index']);
    Route::get('/getProfile', [UserProfileController::class, 'getUserProfile']);
    Route::post('/updateProfile', [UserProfileController::class, 'updateProfile']);
    Route::group(['middleware' => ['role:admin|super-admin',]], function () {
        Route::get('/getUsersList/{page}/{word?}', [UsersListController::class, 'show']);
        Route::get('/deleteUser/{id}', [UsersListController::class, 'destroy']);
        Route::post('/dashboard/newMainCategory', [ItemsDashboardController::class, 'newMainCategory']);
        Route::post('/dashboard/newCategory', [ItemsDashboardController::class, 'newCategory']);
        Route::get('/dashboard/getCategories', [ItemsDashboardController::class, 'getCategories']);
        Route::get('/dashboard/getMainCategories', [ItemsDashboardController::class, 'getMainCategories']);
        Route::post('/dashboard/newItem', [ItemsDashboardController::class, 'newItem']);
    });
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth']], function() {
    Route::post('roles', [RoleController::class, 'getRoles']);
});


