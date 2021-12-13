<?php

// this file will contain all admin role and permission related route 

Route::group(['prefix'=>'admin','middleware'=>['auth:admin','permission']],function(){

  Route::get('/',[
    'as' => 'admin.dashboard',
    'uses' => 'Dashboard\DashboardController@index',
  ]);
  Route::resource('role','Admin\RoleController'); 
  Route::get('role/delete/{id}','Admin\RoleController@destroy');
  Route::post('role/update/{id}','Admin\RoleController@update');
  Route::post('permission','Admin\RoleController@Permission');
  Route::get('role-list','Admin\RoleController@RoleList');
  Route::get('all-role','Admin\RoleController@allRole');

  Route::get('dashboard/summary','Dashboard\DashboardController@show');
  
  // admin manage 
  Route::resource('admin','Admin\AdminController');
  Route::get('admin/delete/{id}','Admin\AdminController@destroy');
  Route::get('admin/{id}/edit','Admin\AdminController@edit');
  Route::get('admin/status/{id}','Admin\AdminController@changeStatus');
  Route::post('admin/update/{id}','Admin\AdminController@update');

  Route::get('admin-list','Admin\AdminController@adminList');
  Route::get('all-area','Admin\AdminController@areaList');

  Route::get('password',[
    'as' => 'change.password',
    'uses' => 'Admin\AdminController@getChangePage',
  ]);
  Route::post('password',[
    'as' => 'admin.changepass.submit',
    'uses' => 'Admin\AdminController@saveChangePass',
  ]);

 });


 // admin register login 

Route::get('admin/login',[
	'as'=>'admin.login',
	'uses'=>'Auth\AdminLoginController@showLoginForm'
]);

Route::post('admin/login', [
	'as'=>'admin.login.submit',
	'uses'=>'Auth\AdminLoginController@login'
]);

Route::get('admin/password-change', [
  'as'=>'password-change.request',
  'uses'=>'Auth\AdminLoginController@showpage'
]);

Route::post('admin/mail-check', [
  'as'=>'admin.chkmail.password',
  'uses'=>'Auth\AdminLoginController@sendmail'
]);

Route::get('admin/reset/{token?}','Auth\AdminLoginController@viewResetPage');

Route::post('confirm-password',[
  'as' => 'admin.confirm.password',
  'uses' => 'Auth\AdminLoginController@storeNewPassword']);

Route::get('admin/logout', [
	'as'=>'admin.logout',
	'uses'=>'Auth\AdminLoginController@logout'
]);