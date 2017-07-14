<?php

//content
Route::get('/content', function () {
    return view('backend.config.content');
})->name('config.content');

//modules
Route::get('/modules/status', 'Config\ModulesController@status');
Route::post('/modules/order', 'Config\ModulesController@order');
Route::resource('/modules', 'Config\ModulesController', ['except' => 'show']);

//sub-modules
Route::get('/modules/{module}/submodules/status', 'Config\SubModulesController@status');
Route::post('/modules/{module}/submodules/order', 'Config\SubModulesController@order');
Route::resource('/modules.submodules', 'Config\SubModulesController', ['except' => 'show']);