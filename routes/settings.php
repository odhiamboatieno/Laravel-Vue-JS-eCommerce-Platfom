<?php

// all kind of setting related route will be here
// we are not using put and delete method because some server does not support put & deletes  method

use App\Http\Controllers\Setting\DeliverySlotSettingController;

Route::group(['prefix' => 'admin/setting', 'middleware' => ['auth:admin', 'permission']], function () {

    // currency setting
    Route::resource('currency', 'Setting\CurrencyController');
    Route::get('currency-list', 'Setting\CurrencyController@currencyList');
    Route::get('currency/{id}/delete', 'Setting\CurrencyController@destroy');
    Route::get('current/currency/{id}', 'Setting\CurrencyController@makeCurrentCurrency');
    Route::post('currency/update/{id}', 'Setting\CurrencyController@Update');
    // currency setting

    // payment gateway setting

    Route::get('payment-gateway-setting', [
        'as'   => 'gateway.index',
        'uses' => 'Setting\PaymentSettingController@index',
    ]);
    Route::get('payment-method-list', 'Setting\PaymentSettingController@paymentMethodList');
    Route::post('payment-gateway/update/{id}', 'Setting\PaymentSettingController@update');

    // payment gateway setting

    // social login credential setting
    Route::get('social/login', [
        'as'   => 'social.index',
        'uses' => 'Setting\SocialCreadentialController@index',
    ]);
    Route::get('social-method-list', 'Setting\SocialCreadentialController@socialMethodList');
    Route::post('social-creadential/update/{id}', 'Setting\SocialCreadentialController@update');
    // social login credential setting

    // Seo Setting
    Route::get('seo', ['as' => 'seo.index', 'uses' => 'Setting\SeoSettingController@index']);

    Route::get('seo/{id}/edit', 'Setting\SeoSettingController@edit');

    Route::post('seo', 'Setting\SeoSettingController@store');
    // Seo Setting

    // Sop Setting
    Route::get('shop', ['as' => 'shop.index', 'uses' => 'Setting\ShopSettingController@index']);

    Route::get('shop/{id}/edit', 'Setting\ShopSettingController@edit');

    Route::post('shop', 'Setting\ShopSettingController@store');
    // Sop Setting

    //Messenger, Google App ID
    Route::get('message', ['as' => 'message.analytics', 'uses' => 'Setting\MessangerController@index']);
    Route::get('get-fb-page-data', 'Setting\MessangerController@getFbId');
    Route::post('set-fb-page-data', 'Setting\MessangerController@setFbId');
    Route::post('change-facebook-id-status', 'Setting\MessangerController@setFbStatus');
    Route::get('get-google-app-data', 'Setting\MessangerController@getGoogleAppId');
    Route::post('set-google-app-data', 'Setting\MessangerController@setGoogleAppId');
    Route::post('change-google-app-status', 'Setting\MessangerController@setGoogleStatus');
    //End Messenger, Google App ID

    //Shipping Route
    Route::get('shipping', ['as' => 'shipping.index', 'uses' => 'Setting\ShippingController@index']);
    Route::get('shipping/discount', 'Setting\ShippingController@getShipping');
    Route::post('shipping/discount', 'Setting\ShippingController@setShipping');
    Route::post('shipping/status', 'Setting\ShippingController@setShippingStatus');
    Route::post('discount/status', 'Setting\ShippingController@setDiscountStatus');

    //Email Route
    Route::get('email', ['as' => 'email.index', 'uses' => 'Email\EmailController@index']);
    Route::get('get-email', 'Email\EmailController@getEmails');
    Route::get('get-user-email', 'Email\EmailController@getUserEmail');
    Route::get('get-subscriber-email', 'Email\EmailController@getSubscriberEmail');
    Route::post('send/email', 'Email\EmailController@send');

    // page route
    Route::get('pages', [
        'as'   => 'pages.index',
        'uses' => 'Setting\PageSettingController@getPage',
    ]);

    Route::get('get-pages', [
        'as'   => 'get-pages',
        'uses' => 'Setting\PageSettingController@getPageData',
    ]);

    Route::post('page-store', [
        'as'   => 'page-store',
        'uses' => 'Setting\PageSettingController@PageStore',
    ]);

    Route::post('page/update/{id}', [
        'as'   => 'page/update',
        'uses' => 'Setting\PageSettingController@update',
    ]);

    Route::get('change/publishing/status/{id}', 'Setting\PageSettingController@changePublish');

    Route::get('page/{id}/delete', [
        'as'   => 'page.delete',
        'uses' => 'Setting\PageSettingController@destroy',
    ]);

    //Trial Setting
    Route::get('trial', ['as' => 'trial.index', 'uses' => 'Setting\TrialController@index']);
    Route::get('trial/{id}/edit', 'Setting\TrialController@edit');
    Route::post('trial', 'Setting\TrialController@update');

// Email Setting
    Route::get('email/setting', ['as' => 'email-setting.index', 'uses' => 'Email\EmailController@showSettingPage']);

    Route::get('mail/data', ['as' => 'mail/data', 'uses' => 'Email\EmailController@getData']);
    Route::post('update/mail', ['as' => 'update/mail', 'uses' => 'Email\EmailController@update']);
    Route::post('mail/status', ['as' => 'mail/status', 'uses' => 'Email\EmailController@status']);

    /****** *****
     * PWA Settings
     ****** *****/
    Route::get('pwa-setting', 'Setting\PwaSettingController@index')->name('pwa.index');
    Route::post('pwa-setting', 'Setting\PwaSettingController@store');

    // date time slot setting
    Route::get('date-slot', [DeliverySlotSettingController::class, 'index'])->name('slot.index');
    Route::get('get-date-slot', [DeliverySlotSettingController::class, 'getSlot']);
    Route::post('date-slot', [DeliverySlotSettingController::class, 'store']);

    Route::resource('time-slot', 'Setting\TimeSlotSettingController');
    Route::get('time-slot-list', 'Setting\TimeSlotSettingController@index');

});

Route::get('enter/code', 'Auth\CodeVerifyController@enterCode')->name('purchase.code');
Route::post('post/code', 'Auth\CodeVerifyController@verifyCode');
Route::get('send-verification', 'Auth\CodeVerifyController@sendVerification');

// get slot by date

Route::get('time-slot/date/{date}', 'Setting\TimeSlotSettingController@getSlotByDate');
