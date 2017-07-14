<?php
/**
 * Users Route
 */

Route::get('/users/{users}/restore', 'User\UserController@restore')
    ->name('users.restore');
Route::resource('/users', 'User\UserController', ['names' => [
    'index' => 'users.index',
    'store' => 'users.store',
    'edit' => 'users.edit',
    'create' => 'users.create',
    'update' => 'users.update',
    'destroy' => 'users.destroy',
],
    'except' => ['show']]);
Route::get('/users/{users}/password-reset', 'User\UserController@resetPassword')
    ->name('users.password');

Route::get('/users/{cod}/edit-permission', 'User\UserController@editPermission')->name('users.edit.permission')
    ->middleware("role:add permission users");
Route::patch('/users/{cod}/edit-permission', 'User\UserController@updatePermission')->name('users.edit.permissionPost');

//profile
Route::get('/profile', 'Config\ProfilesController@index')->name('profile.index');
Route::get('/profile/edit', 'Config\ProfilesController@edit')->name('profile.edit');
Route::patch('/profile/edit', 'Config\ProfilesController@update')->name('profile.update');
Route::get('/profile/edit-password', 'Config\ProfilesController@editPassword')->name('profile.password.edit');
Route::patch('/profile/edit-password', 'Config\ProfilesController@updatePassword')->name('profile.password.update');