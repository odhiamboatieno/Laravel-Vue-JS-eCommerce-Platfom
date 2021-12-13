<?php

// this file will contain all offer related routes 

Route::group(['prefix'=>'admin','middleware'=>['auth:admin','permission']],function(){
   
  Route::resource('offer','Offers\CampaignController');
  Route::get('offer-list','Offers\CampaignController@offerList');
  Route::get('offer/{id}/delete','Offers\CampaignController@destroy');
  Route::post('offer/{id}/update','Offers\CampaignController@update');
  Route::get('offer/product-list/search','Offers\CampaignController@productList');
  Route::get('offer/status/{id}','Offers\CampaignController@offerStatus');

  // Slider
  Route::resource('slider','Offers\SliderController');
  Route::get('slider-list','Offers\SliderController@sliderList');
  Route::get('slider-status/{id}','Offers\SliderController@sliderStatus');
  
  // Coupon
  Route::resource('coupon','Offers\CouponController');
  Route::get('coupon-list','Offers\CouponController@couponList');
  
  // Send Coupon
  Route::resource('send-coupon','Offers\SendCouponController');
  Route::get('customer-coupon-list','Offers\SendCouponController@customerCouponList');
  Route::post('get-customer-for-coupon','Offers\SendCouponController@getCustomer');
 });

//  aplly coupon 

 Route::get('user-coupon','Offers\CouponController@userCoupon');
 Route::post('apply-coupon','Offers\CouponController@applyCoupon');