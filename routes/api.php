<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\UsersListController;
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
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/getItems', [ItemController::class, 'index']);
    Route::get('/getProfile', [UserProfileController::class, 'getUserProfile']);
    Route::post('/updateProfile', [UserProfileController::class, 'updateProfile']);
    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('/getUsersList/{page}/{word?}', [UsersListController::class, 'show']);
        Route::get('/deleteUser/{id}', [UsersListController::class, 'destroy']);
    });
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth']], function() {
    Route::post('roles', [RoleController::class, 'getRoles']);
});

