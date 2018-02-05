<?php
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    if (getLoginUid()){
        return redirect('home/index');
    }else{
        return view('home.index.login');
    }
});

/**
 * 首页模块
 */
Route::group(['namespace' => 'Home','middleware' => ['web']], function () {
    Route::controllers ( [
        'home' => 'HomeController',
    ] );
});
/**
 * 管理模块
 */
Route::group(['namespace' => 'Admin','middleware' => ['web']], function () {
    Route::controllers ( [
        'menu' => 'MenuController',
        'auth' => 'AuthController',
        'role' => 'RoleController',
    ] );
});

