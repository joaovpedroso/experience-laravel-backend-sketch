<?php
/**
 * Created by PhpStorm.
 * User: Lucas Mota
 * Date: 27/03/17
 * Time: 09:48
 */

Route::get('/login', 'Auth\LoginController@index')->name('login')->middleware('guest');
Route::post('/login', 'Auth\LoginController@login');
Route::get('/logout', 'Auth\LoginController@logout')->middleware('auth');