<?php

namespace App\Http\Controllers;

use App\Wallet;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class WalletController extends BaseController{


/**
 * get wallet
 *
 * @return void
 */
public function getWallet(){
    $wallet = Wallet::all();
    return response()->json($wallet, 200);
}
 /**
 * Create a new wallet.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return void
 */
 public function createWallet(Request $request){

  // user input
  $this->validate($request, [
    'wallet_id' => 'string|required',
    'pos' => 'int|required',
    'web' => 'int|required',
    'android' => 'int|required',
    'android_pos' => 'int|required'
  ]);

  // check wallet id meets criteria
  $wallet_id = $request->wallet_id;

  if($wallet_id !== null){
   $expectedWallet_id=[
    '68430147',
    '19003498'
   ];

   if(!in_array($wallet_id, $expectedWallet_id)){
    // // set channels
    // $pos = 1;
    // $web = 1;
    // $android = 0;
    // $android_pos = 1;
    return response()->json(['error'=>'wallet_id not found']);
   }

   // store to database
   $wallet = new Wallet();
   $wallet->wallet_id = $wallet_id;
   $wallet->pos = $request->pos;
   $wallet->web = $request->web;
   $wallet->android = $request->android;
   $wallet->android_pos = $request->android_pos;
   $wallet->save();
   
   // return value
   return response()->json($wallet, 200);
  }
  
  //return null;
  
 }

 /**
 * update a wallet.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return void
 */
 public function updateWallet(Request $request, $id){
  // $wallet_id = $id;
  $wallet = Wallet::findOrFail($id);
  $wallet->wallet_id = $request->wallet_id;
  $wallet->pos = $request->pos;
  $wallet->web = $request->web;
  $wallet->android = $request->android;
  $wallet->android_pos = $request->android_pos;
  $wallet->save();

  // return value
  return response()->json($wallet, 200);
 }

 
}