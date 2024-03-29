<?php

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
$router->get('/', function(){
    return app()->environment();
});

$router->group(['prefix' => 'api/v1'], function() use($router){
    $router->get('/wallet/{wallet_id}', 'WalletController@getWallet');
    $router->post('/wallet', 'WalletController@createWallet');
    $router->post('/wallet/checkrestriction', 'WalletController@set_wallet_channel');
});