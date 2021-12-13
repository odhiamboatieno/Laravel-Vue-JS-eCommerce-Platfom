<?php

/* containing backend route of category subcategory ,

 sub_sub_category and products */

Route::group(['prefix'=>'admin','middleware'=>['auth:admin','permission']],function(){
  
  // category in admin 

  Route::resource('category','Product\CategoryController');
  
  Route::post('category/update/{id}',[
   'as' => 'update.category',
   'uses' => 'Product\CategoryController@update',
  ]);  

  Route::get('category/delete/{id}',[
   'as' => 'delete.category',
   'uses' => 'Product\CategoryController@destroy',
  ]);

  Route::get('category-list','Product\CategoryController@categoryList');

  // brand in admin
  Route::resource('brand','Product\BrandController');

  Route::post('brand/update/{id}',[
   'as' => 'update.brand',
   'uses' => 'Product\BrandController@update',
  ]);  

  Route::get('brand/delete/{id}',[
   'as' => 'delete.brand',
   'uses' => 'Product\BrandController@destroy',
  ]);

  Route::get('brand-list','Product\BrandController@brandList'); 

  // sub category in admin  

  Route::resource('sub-category','Product\SubCategoryController');

  Route::post('sub-category/update/{id}',[
   'as' => 'update.subcategory',
   'uses' => 'Product\SubCategoryController@update',
  ]);  

  Route::get('sub-category/delete/{id}',[
   'as' => 'delete.subcategory',
   'uses' => 'Product\SubCategoryController@destroy',
  ]);

  Route::get('sub-category-list','Product\SubCategoryController@subCategoryList'); 

  // general thing 

  // sub sub category in admin
  //resource route will have edit method index post and create mehtod

  Route::resource('sub-sub-category','Product\SubSubCategoryController');

  Route::post('sub-sub-category/update/{id}',[
    'as' => 'update.subcategory',
    'uses' => 'Product\SubSubCategoryController@update',
   ]);  
 
   Route::get('sub-sub-category/delete/{id}',[
    'as' => 'delete.subcategory',
    'uses' => 'Product\SubSubCategoryController@destroy',
   ]);
 
   Route::get('sub-sub-category-list','Product\SubSubCategoryController@subSubCategoryList'); 
  
  // get sub category under a category in ajax call you may pass a additional ?edit_time peram

  Route::get('get-subcategory/{category_id}','Product\SubCategoryController@getSubCategory');
  Route::get('get-sizes/{category_id}','Product\SizeController@getSizeByCategory');
  // get sub sub category under a subcategory 
  Route::get('get-sub-subcategory/{sub_category_id}','Product\SubSubCategoryController@getSubSubCategory');
  Route::get('get-color','Product\ColorController@getColor');
  // get those brand which is assign to a specific sub_sub_category   
  Route::get('get-brand/{sub_sub_category_id}','Product\SubSubCategoryController@getSubSubCategoryBrand');

  // sub sub category in admin  

  Route::resource('sub-sub-category','Product\SubSubCategoryController');
  Route::get('sub-sub-category-list','Product\SubSubCategoryController@subSubCategoryList');
 

  // product in admin
  Route::resource('product','Product\ProductController');
  Route::post('product/update/{id}',[
    'as'    =>  'product.update',
    'uses'  =>'Product\ProductController@update'
  ]);

  Route::get('product/delete/{id}',[
   'as'   => 'product.subcategory',
   'uses' => 'Product\ProductController@destroy',
  ]);

  Route::get('product/deactive/{id}','Product\ProductController@deactiveProduct');

  Route::get('all-categories/{edit_time}','Product\ProductController@getAllCategory');

  Route::get('product-list','Product\ProductController@productList');

  // product multiple image single delete 

  Route::get('product/image/{id}/delete','Product\ProductController@deleteImage');
  Route::get('product/hotdeal-status/{id}','Product\ProductController@hotDealStatus');

// Route On product Discount
  Route::get('product/{id}/discount','Product\ProductController@getForDiscount');
  Route::post('set-discount','Product\ProductController@setDiscount');

  // Route On product Color
  Route::resource('product-color','Product\ColorController');
  Route::get('color-list','Product\ColorController@colorList');

  // Route On product Size
  Route::resource('product-size','Product\SizeController');
  Route::get('size-list','Product\SizeController@sizeList');

});