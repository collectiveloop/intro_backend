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
Route::get('remember-link/{token}', 'UsersController@redirectLink');
Route::get('invitations-link', 'ContactsController@redirectLink');
Route::get('intros-link', 'IntrosController@redirectLink');
Route::group(['prefix' => 'administration'], function(){
  Route::group(['middleware' => ['validate.admin_session']], function(){

  });
  Route::group(['middleware' => 'auth'], function() {

  });
});

Route::group(['prefix' => 'login'], function(){
  Route::post('authenticate/{lang?}', 'UsersController@authenticate');
  Route::post('forgot-password/{lang?}', 'UsersController@forgotPassword');
  Route::post('reset-password/{lang}', 'UsersController@resetPassword');
});

Route::group(['prefix' => 'user'], function(){
  Route::post('{lang?}', 'UsersController@createUser');
  Route::post('external/{lang?}', 'UsersController@createExternalUser');
  Route::post('change-password/{lang}', 'UsersController@changePassword');
});

Route::group(['middleware' => ['validate.session']], function(){
  Route::group(['prefix' => 'user'], function(){
    Route::get('{lang?}', 'UsersController@getUser');
    Route::get('/{lang?}/basic', 'UsersController@getBasicUser');
    Route::put('{lang?}', 'UsersController@updateUser');
  });
  // --Login--
  Route::group(['prefix' => 'login'], function(){
    Route::get('logout', 'UsersController@closeSession');
    Route::get('token', 'UsersController@getToken');
  });

  Route::group(['prefix' => 'gainings'], function(){
    Route::get('/{lang?}', 'GainingsController@getGainings');
    Route::get('/{lang?}/{id}', 'GainingsController@getGain');
  });

  // --CONTACTOS--
  Route::group(['prefix' => 'contacts'], function ($app) {
    Route::get('/{lang?}/page/{page}/quantity/{quantity}', 'ContactsController@getContactsPaginate');
    Route::get('/{lang?}/count', 'ContactsController@getCountContacts');
    Route::get('/{lang?}/pending/page/{page}/quantity/{quantity}', 'ContactsController@getContactsPendingPaginate');
    Route::get('/{lang?}/pending/count', 'ContactsController@getCountContactsPending');
    Route::get('/{lang?}/search/page/{page}/quantity/{quantity}/{find?}', 'ContactsController@getContactsMixedPaginate');
    Route::get('/{lang?}/{id}', 'ContactsController@getContact');
    Route::get('/{lang?}/find/{email}', 'ContactsController@findContact');
    Route::post('{lang?}', 'ContactsController@addContact');
    Route::put('{lang?}/accept/{id}', 'ContactsController@acceptInvitation');
    Route::put('{lang?}/reject/{id}', 'ContactsController@rejectInvitation');
    Route::put('{lang?}/own-reject/{id}', 'ContactsController@rejectOwnInvitation');

    Route::put('{lang?}/{id}', 'ContactsController@updateContact');
    Route::delete('{lang?}/{id}', 'ContactsController@deleteContact');
  });

  Route::group(['prefix' => 'intros'], function(){
    Route::get('/dashboard/{lang?}', 'IntrosController@getIntrosDashBoard');
    Route::post('{lang?}', 'IntrosController@addIntro');
    Route::get('{lang?}/{id}', 'ListIntrosController@getDetailIntro');
    Route::get('/{lang?}/made/page/{page}/quantity/{quantity}', 'ListIntrosController@getMadeIntrosPaginate');
    Route::get('/{lang?}/made/count', 'ListIntrosController@getCountMadeIntros');
    Route::get('/{lang?}/received/page/{page}/quantity/{quantity}', 'ListIntrosController@getReceivedIntrosPaginate');
    Route::get('/{lang?}/received/count', 'ListIntrosController@getCountReceivedIntros');
  });

  Route::group(['prefix' => 'messages'], function(){
    Route::get('/{lang?}/received/page/{page}/quantity/{quantity}', 'MessagesController@getReceivedMembersPaginate');
    Route::get('/{lang?}/received/count', 'MessagesController@getCountReceivedMembers');
  });

});
