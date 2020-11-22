<?php 
///transactions
Route::group(['namespace' => 'Kataki\Syber_pay\Http\Controllers','middleware' => config('Syber_pay.middleware') ,'prefix' => 'api/transactions'], function () {
    Route::post('syber/payment', 'PaymentController@SyberPay');
    Route::post('syber/notify', 'PaymentController@notify');
    Route::post('syber/return', 'PaymentController@return');
    Route::post('syber/cancel', 'PaymentController@cancel');
    

});



?>