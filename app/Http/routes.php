<?php

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

Route::pattern('id', '[0-9]+');
Route::group(['prefix' => 'administration'], function(){
  Route::group(['middleware' => ['validate.admin_session']], function(){
    Route::get('dashboard/{lang?}', 'AdministrationController@dashboard');
    Route::get('remember/{lang?}/{token}', 'AdministrationController@remember');
  });
  Route::group(['middleware' => 'auth'], function() {
    Route::match(['get', 'post'], 'login/{lang?}', 'AdministrationController@login' );
  });
});

Route::group(['prefix' => 'login'], function(){
    Route::post('authenticate/{lang?}', 'UsersController@authenticate');
    Route::get('logout', 'UsersController@closeSession');
});

Route::group(['prefix' => 'user'], function(){
    Route::post('{lang?}', 'UsersController@createUser');
    Route::post('external/{lang?}', 'UsersController@createExternalUser');
    Route::put('/setting/{id}/{lang?}', 'UsersController@createSettingUser');
});

Route::group(['middleware' => ['validate.session']], function(){
  Route::group(['prefix' => 'user'], function(){
      Route::get('{lang?}', 'UsersController@getUser');
      Route::put('{lang?}', 'UsersController@updateUser');
  });
    // --Login--
    Route::group(['prefix' => 'login'], function(){
        Route::get('token', 'UsersController@getToken');
    });

    // --PRODUCTOS--
    Route::group(['prefix' => 'products'], function ($app) {
      Route::get('/{lang?}/page/{page}/quantity/{quantity}', 'ProductsController@getProductsPaginate');
      Route::get('/{lang?}/count', 'ProductsController@getCountProducts');
      Route::get('/{lang?}/{id}', 'ProductsController@getProduct');
    	Route::post('/', 'ConfigController@createConfig');
    	Route::put('{id}', 'ConfigController@updateConfig');
    	Route::delete('{id}', 'ConfigController@deleteConfig');
    });
});
