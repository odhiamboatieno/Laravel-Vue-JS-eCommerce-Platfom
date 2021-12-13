<?php

// all order related route are here 
Route::group(['prefix'=>'admin','middleware'=>['auth:admin','permission']],function(){

	Route::resource('order','Order\OrderController');
	Route::get('order-list','Order\OrderController@orderList');
	Route::get('order/delete/{id}','Order\OrderController@destroy');
	Route::get('order/pdf/{id}','Order\OrderController@pdfLoad');
	Route::get('order/print/{id}','Order\OrderController@printLoad');
	Route::get('all-cities','Order\OrderController@getCity');
	Route::get('order/payment-status/{id}','Order\OrderController@changePaymentStatus');
	Route::get('order/{value}/process-status/{id}','Order\OrderController@changeProcessStatus');
	Route::get('order/delete/single-item/{order_id}/{id}','Order\OrderController@deleteSingleOrder');
	Route::get('order-invoice-send/{id}','Order\OrderController@orderDetails');
	Route::post('send-sms-invoice-to-customer','Order\OrderController@sendMail');

	Route::post('order/item-increment/{id}','Order\OrderController@increamentSingleItem');
	
	// manage trial 
	Route::post('trial-to-invoice/{id}','Order\OrderController@trialToInvoice');
	Route::get('order/delete/trial-item/{id}','Order\OrderController@deleteTrialItem');
	

	Route::get('area-wise-order',[
		'as'   => 'areawiseorder.index',
		'uses' => 'Order\OrderController@viewAreaOrder'
	]);

	Route::get('shop/address','Setting\ShopSettingController@getAddress');

	// Shipping Area
	Route::get('order-area',['as' => 'order-area.index','uses' => 'Setting\ShippingAreaController@index']);
	Route::get('order-area-list','Setting\ShippingAreaController@getAreaData');
	Route::get('change/shipping/status/{id}','Setting\ShippingAreaController@changeStatus');
	Route::get('area/delete/{id}','Setting\ShippingAreaController@destroy');
	Route::post('area/store','Setting\ShippingAreaController@store');
	

});

// order tracking 

Route::get('order-track',['as'=>'order.track','uses' => 'Order\OrderController@orderTrack']);

Route::post('order-track','Order\OrderController@orderTrackPost');