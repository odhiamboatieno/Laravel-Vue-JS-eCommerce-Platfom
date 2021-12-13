<?php

Route::group(['prefix'=>'admin','middleware'=>['auth:admin','permission']],function(){

Route::get('category-stock/chart', 'Chart\ReportController@getCatStock');
Route::get('last-month/order/chart', 'Chart\ReportController@getOrderData');
Route::get('last-month/customer/chart', 'Chart\ReportController@getCustomerData');
Route::get('sales-amount/chart', 'Chart\ReportController@getSaleAmount');

Route::get('stock-report',['as' => 'stock.report','uses' => 'Chart\ReportController@stockReport']);
Route::get('sales-report',['as' => 'sales.report','uses' => 'Chart\ReportController@salesReport']);
Route::get('invoice-report',['as' => 'invoice.report','uses' => 'Chart\ReportController@invoiceReport']);

Route::get('product-report-pdf','Chart\ReportController@productListPdf');
Route::get('product-report-print','Chart\ReportController@productListPrint');

Route::get('product-sale-report','Chart\ReportController@saleList');
Route::get('product-sales-report-pdf','Chart\ReportController@salesListPdf');
Route::get('product-sales-report-print','Chart\ReportController@salesListPrint');

Route::get('product-invoice-report','Chart\ReportController@productInvoiceList');
Route::get('product-invoice-report-pdf','Chart\ReportController@invoiceListPdf');
Route::get('product-invoice-report-print','Chart\ReportController@invoiceListPrint');

Route::get('transaction',['as' => 'transection.report','uses' => 'Chart\ReportController@showTransection']);

Route::get('get-payment-method','Chart\ReportController@getMethods');
Route::get('payment-method-wise-amount','Chart\ReportController@getMethodAmount');
Route::get('product-amount-report-pdf','Chart\ReportController@amountListPdf');
Route::get('product-amount-report-print','Chart\ReportController@amountListPrint');

Route::get('export', 'Chart\ReportController@export')->name('export');
Route::post('import','Chart\ReportController@import')->name('import');

});