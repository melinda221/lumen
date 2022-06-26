<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/



$router->group(['middleware' => 'auth'],function() use ($router){
    //Disini route utama kalian
    // $router->post('/test/payments', 'PaymentsController@testPayment');
    $router->post('/buy/product/submit','PaymentsController@buyProduct');
    $router->post('/payment/notification/push','NotificationController@post');
   
});