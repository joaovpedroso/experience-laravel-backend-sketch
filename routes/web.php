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


    /**
     * news
     */
    Route::get('/editorials/status', 'Config\EditorialsController@status');
    Route::resource('/editorials', 'Config\EditorialsController', ['except' => 'show']);
    //news
    Route::get('/noticias/status', 'Content\NewsController@status');
    Route::get('content/noticias/trash/status', 'Content\NewsController@status');
    Route::post('content/noticias/cover', 'Content\NewsController@cover');
    Route::get('content/noticias/featured', 'Content\NewsController@featured');
    Route::get('/noticias/{news}/restore', 'Content\NewsController@restore')
        ->name('content.news.restore');
    Route::get('/noticias/trash', 'Content\NewsController@trash')
        ->name('content.news.trash');
    Route::get('/noticias/{news}/photos', 'Content\NewsController@photos')
        ->name('content.news.photos');
    Route::post('/noticias/{news}/photos', 'Content\NewsController@upload')
        ->name('content.news.upload');
    Route::post('/noticias/caption/{photo}', 'Content\NewsController@caption')
        ->name('content.news.photos.caption');
    Route::delete('/noticias/delete/{photo}', 'Content\NewsController@delPhoto')
        ->name('content.news.photos.delete');
    Route::post('content/noticias/photos/order', 'Content\NewsController@order');
    Route::resource('/noticias', 'Content\NewsController', ['except' => 'show', 'names' => [
        'index' => 'content.news.index',
        'create' => 'content.news.create',
        'store' => 'content.news.store',
        'edit' => 'content.news.edit',
        'update' => 'content.news.update',
        'destroy' => 'content.news.destroy'
    ]]);
    Route::get('/news/photos/{folder}/{filename}', function ($folder, $filename) {
        $path = storage_path().'/app/img/news/'.$folder.'/'.$filename;
        if (!\Illuminate\Support\Facades\File::exists($path))
            abort(404);

        return Image::make($path)->response();
    });
});