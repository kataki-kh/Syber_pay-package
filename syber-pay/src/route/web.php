<?php 
///transactions
Route::group(['namespace' => 'Kataki\Syber_pay\Http\Controllers','middleware' => env('middleware') ,'prefix' => 'transactions'], function () {
    Route::get('syber/payment', 'PaymentController@SyberPay');
    Route::get('syber/notify', 'PaymentController@notify');
    Route::get('syber/return', 'PaymentController@return');
    Route::get('syber/cancel', 'PaymentController@cancel');
    

});



?>