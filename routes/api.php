<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::group(['controller' => ResetPasswordController::class], function (){
    // Request password reset link
    Route::post('forgot-password', 'sendResetLinkEmail')->middleware('guest')->name('password.email');
    // Reset password
    Route::post('`reset-password`', 'resetPassword')->middleware('guest')->name('password.update');

    Route::get('reset-password/{token}', function (string $token)
    {
         return $token;
     })->middleware('guest')->name('password.reset');
});


Route::group(['middleware'=>'auth:sanctum'], function()
{
    // Profile
    Route::put('user/{user}', [ProfileController::class, 'updateProfile'])->middleware('permission:edit my profile|edit every profile');
    Route::delete('user/{user}', [ProfileController::class, 'deleteProfile'])->middleware('permission:delete my profile|delete every profile');
    Route::post('logout', [ProfileController::class,'logout']);
    Route::post('refresh', [ProfileController::class,'refresh']);


    // Products
    Route::group(['controller' => ProductController::class], function ()
    {
        Route::get('products', 'index');
        Route::post('product', 'store')->middleware('permission:add product');
        Route::get('product/{id}', 'show')->middleware('permission:show product');
        Route::put('product/{id}', 'update')->middleware('permission:edit every product|edit my category');
        Route::delete('product/{id}', 'destroy')->middleware('permission:delete every product|delete my product');
    });
    // Categories
    Route::group(['controller' => CategoryController::class], function ()
    {
        Route::get('categories', 'index')->middleware('permission:show category');
        Route::post('category', 'store')->middleware('permission:add category');
        Route::get('category/{id}', 'show')->middleware('permission:show category');
        Route::put('category/{id}', 'update')->middleware('permission:edit category');
        Route::delete('category/{id}', 'destroy')->middleware('permission:delete category');
    });
    // Roles
    Route::group(['controller' => RoleController::class], function()
    {
        Route::get('roles', 'index')->middleware('permission:show role');
        Route::post('role', 'store')->middleware('permission:add role');
        Route::get('role/{id}', 'show')->middleware('permission:show role');
        Route::put('role/{id}', 'update')->middleware('permission:edit role');
        Route::delete('role/{id}', 'destroy')->middleware('permission:delete role');
        Route::post('assign-role/{id}', 'assignRole')->middleware('permission:assign role');
        Route::post('remove-role/{id}', 'removeRole')->middleware('permission:assign role');

    });
    Route::post('assign-permission/{role}', [PermissionController::class,'assignPermissionToRole'])->middleware('permission:assign permission');
    Route::delete('remove-permission/{role}', [PermissionController::class,'removePermissionFromRole'])->middleware('permission:assign permission');
});


Route::get('filter/{category_name}', [ProductController::class, 'filterByCategory']);
