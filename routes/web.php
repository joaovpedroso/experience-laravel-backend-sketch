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

header('Access-Control-Allow-Headers: https://viacep.com.br');
header('Access-Control-Allow-Credentials: true');

Route::get('/', 'Home\HomeController@index')->middleware('auth');

require_once "routes/auth/routes.php";


Route::group(['middleware' => 'auth'], function () {
    /**
     * Log
     */
    Route::get('/log', [
        'uses' => 'LogsController@index',
        'as' => 'logs.index',
        'middleware' => 'acl:/logs'
    ]);
    /**
     * Config
     */
    Route::group(['prefix' => 'config', 'middleware' => 'acl:/config'], function () {
        Route::get('/painel', 'Config\ModulesController@painel')->name('config.painel');
        Route::get('/configuration', 'Config\ModulesController@index')->name('config.index');
        Route::get('/configuration/status', 'Config\ModulesController@status')->name('config.status');
        Route::get('/configuration/modules', 'Config\ModulesController@configModules')->name('modules.config');
        Route::get('/configuration/info', 'Config\ModulesController@info')->name('config.info');
        Route::patch('/configuration/info/update', 'Config\ModulesController@infoUpdate')->name('config.info.update');
        Route::patch('/configuration/modules/update', 'Config\ModulesController@updateConfigModules')->name('modules.config.update');
        require 'routes/config/routes.php';
    });

    /**
     * User routes
     */
    require_once "routes/user/routes.php";

});