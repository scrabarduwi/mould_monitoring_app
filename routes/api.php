<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// Passport 
Route::group(['middleware' => 'auth:api'], function(){
    Route::get('/private/user_info',function(){
        return Auth::user();
    });

    Route::post('/private/mould', 'MouldController@store');
    Route::get('/private/mould', 'MouldController@index');
    Route::put('/private/mould/{id}/{img}', 'MouldController@update');
    Route::post('/private/mould/delete', 'MouldController@deleteMould');

    // Ring Category API
    Route::post('/private/ring-category', 'RingCategoryController@store');
    Route::get('/private/ring-category', 'RingCategoryController@index');
    Route::put('/private/ring-category/{id}/{img}', 'RingCategoryController@update');
    Route::post('/private/ring-category/delete', 'RingCategoryController@deleteRing');

    // Client API
    Route::post('/private/client', 'ClientController@store');
    Route::get('/private/client', 'ClientController@index');
    Route::put('/private/client/{id}', 'ClientController@update');
    Route::post('/private/client/delete', 'ClientController@deleteClient');

    // User API
    Route::get('/private/user', 'ClientController@getAllUser');
    Route::post('/private/user/{roles}/{id}', 'ClientController@updateUser');
});