<?php

use App\Http\Controllers\Front\WebController;

Route::get('/', 'Front\WebController@index');
Route::get('page/{slug}', 'Front\WebController@ShowPage');
Route::get('category-list', 'Front\WebController@categoryList');
Route::get('home-offers', 'Front\WebController@homeOffers');
Route::get('hot-deal', 'Front\WebController@hotDeal');
Route::get('on-sale', 'Front\WebController@onSale');
Route::get('offers', 'Offers\CampaignController@allOffer');
Route::get('offer/{id}/{name}', 'Offers\CampaignController@getOffer')->name('all-offer.product');

Route::get('related-product/{sub_cat_id}/{id}', 'Front\WebController@relatedDeal');

Route::post('user/subscribe', 'Front\WebController@subscribe');

Route::get('product/category/{id}/{slug}', 'Front\WebController@categoryProduct')->name('category.product')->where('slug', '.*');

// product details

// category product
Route::get('product/category/{id}/{slug?}', 'Front\WebController@categoryProduct')->name('category.product')->where('slug', '.*');

// sub category product
Route::get('product/sub-category/{id}/{slug?}', 'Front\WebController@subCategory')->name('subcategory.product')->where('slug', '.*');

// sub category product
Route::get('product/sub-sub-category/{id}/{slug?}', 'Front\WebController@subSubCategory')->name('subsubcategory.product')->where('slug', '.*');

// product details

Route::get('product/{id}/{slug}', 'Front\WebController@productDetails')->name('product.details')->where('slug', '.*');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Login with Social media
Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('product-list', [WebController::class, 'productList']);
