<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// auth controller
    use App\Http\Controllers\Api\Auth\AuthController;
// ===============
// setting
    use App\Http\Controllers\Api\Setting\MenuController;
    use App\Http\Controllers\Api\Setting\RoleController;
    use App\Http\Controllers\Api\Setting\UserController;
    use App\Http\Controllers\Api\Setting\LogController;
// ===============
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
// login / register route
    Route::group(['prefix'=>'auth','as'=>'auth.'], function(){
        Route::post('login', [AuthController::class, 'loginapi'])->name('auth-login');
    });
// ==========
// sanctum
    Route::group(['middleware' => 'auth:sanctum'], function () {
        // auth
            Route::group(['prefix'=>'auth','as'=>'auth.'], function(){
                Route::get('/user', [AuthController::class, 'getuserdetailapi'])->name('auth-user');
            });
        // ====
        // setting
            Route::group(['prefix'=>'setting','as'=>'setting.'], function(){
                // menu
                    Route::group(['prefix'=>'menu','as'=>'menu.'], function(){
                        Route::get('/', [MenuController::class, 'listapi'])->name('menu');
                        Route::get('/select', [MenuController::class, 'selectlistapi'])->name('menu-select');
                        Route::get('/detail', [MenuController::class, 'getdatadetailapi'])->name('menu-detail');
                        
                        Route::post('/store', [MenuController::class, 'storeapi'])->name('menu-store');
                        Route::post('/update', [MenuController::class, 'updateapi'])->name('menu-update');
                        Route::post('/destroy', [MenuController::class, 'destroyapi'])->name('menu-destroy');
                    });
                // ====
                // role
                    Route::group(['prefix'=>'role','as'=>'role.'], function(){
                        Route::get('/', [RoleController::class, 'listapi'])->name('role');
                        Route::get('/select', [RoleController::class, 'selectlistapi'])->name('role-select');
                        Route::get('/detail', [RoleController::class, 'getdatadetailapi'])->name('role-detail');

                        Route::post('/store', [RoleController::class, 'storeapi'])->name('role-store');
                        Route::post('/update', [RoleController::class, 'updateapi'])->name('role-update');
                        Route::post('/destroy', [RoleController::class, 'destroyapi'])->name('role-destroy');
                    });
                // ====
                // user
                    Route::group(['prefix'=>'user','as'=>'user.'], function(){
                        Route::get('/', [UserController::class, 'listapi'])->name('user');
                        Route::get('/select', [UserController::class, 'selectlistapi'])->name('user-select');
                        Route::get('/detail', [UserController::class, 'getdatadetailapi'])->name('user-detail');

                        Route::post('/store', [UserController::class, 'storeapi'])->name('user-store');
                        Route::post('/update', [UserController::class, 'updateapi'])->name('user-update');
                        Route::post('/destroy', [UserController::class, 'destroyapi'])->name('user-destroy');
                    });
                // ====
                // user
                    Route::group(['prefix'=>'log','as'=>'log.'], function(){
                        Route::get('/', [LogController::class, 'listapi'])->name('user');
                    });
                // ====
            });
        // =======
    });
// ==========
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
