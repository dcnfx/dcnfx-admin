<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//验证码
Route::get('/verify',                   'Admin\HomeController@verify')->name('admin.verify.verify');
//登陆模块
//Auth::routes();
Route::group(['namespace'  => "Auth",'prefix'=>'admin'], function () {
    Route::get('/login',                'LoginController@showLoginForm')->name('admin.login.showLoginForm');
    Route::post('/login',               'LoginController@login')->name('admin.login.login');;
    Route::get('/logout',               'LoginController@logout')->name('admin.logout');
});
//后台主要模块
Route::group(['namespace'  => "Admin",'middleware' => ['auth', 'permission'],'prefix'=>'admin'], function () {
    Route::get('/',                     'HomeController@index');
    Route::get('/gewt',                 'HomeController@configr');
    Route::get('/index',                'HomeController@welcome')->name('admin.index');
    Route::post('/sort',                'HomeController@changeSort')->name('admin.sort');
    Route::get('/userinfo',             'UserController@userInfo')->name('admin.userinfo');
    Route::post('/saveinfo/{type}',     'UserController@saveInfo');
    Route::resource('/menus',           'MenuController');
    Route::resource('/logs',            'LogController');
    Route::resource('/users',           'UserController');
    Route::resource('/roles',           'RoleController');
    Route::resource('/permissions',     'PermissionController');
});

/**
 *  Route::resource('/users', 'UsersController'); 等同于
 * Route::get('/users', 'UsersController@index')->name('users.index');
 * Route::get('/users/{user}', 'UsersController@show')->name('users.show');
 * Route::get('/users/create', 'UsersController@create')->name('users.create');
 * Route::post('/users', 'UsersController@store')->name('users.store');
 * Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');
 * Route::delete('/users/{user}', 'UsersController@destroy')->name('users.destroy');
 *
 * Route::patch('/users/{user}', 'UsersController@update')->name('users.update');

 *
 */
