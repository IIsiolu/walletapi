<?php

namespace App\Http\Controllers;

use App\Wallet;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class WalletController extends BaseController{

  /**
   * set wallet & channel
   *
   * @return void
   */
  public function set_wallet_channel(Request $request){

    // user input
    $this->validate($request, [
      'wallet_id' => 'bail|string|required|min:8|max:8',
      'channel' => 'string|required',
    ]);
    
    $wallet_details = $this->getWallet($request->wallet_id);

    // print_r($wallet_details[0][$request->channel]);

    $restricted_wallet = $this->check_for_restricted_wallet($wallet_details, $request->channel);

    // return $walletChannelRequest;
    if($restricted_wallet == 2){
      return response()->json([
        'msg' => 'ensure channel in pos, web, android, android_pos',
      ]);
    }
    
    if($restricted_wallet == 4){
      return response()->json([
        'msg' => 'wallet is not restricted.',
      ]);
    }
    
    if($restricted_wallet == 9){
      return response()->json([
        'msg' => 'wallet is restricted.',
      ]);
    }

  }


  /**
   * get wallet
   *
   * @return void
   */
  public function getWallet($wallet_id){
      $wallet = Wallet::where('wallet_id', $wallet_id)->get();
      // print_r($wallet);
      $json = json_encode($wallet);
      $phpArray = json_decode($json, true);
      return $phpArray;
  }

  public function check_for_restricted_wallet($wallet_details, $channel){

    $storedChannels = array_keys($wallet_details[0]);
    array_shift($storedChannels);
    array_pop($storedChannels);
    array_pop($storedChannels);
    print_r($storedChannels);

    if(in_array($channel, $storedChannels)){
      if($wallet_details[0][$channel] == 0){
        return 9;
      }
      return 4;
    }
    return 2;

    
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
    'wallet_id' => 'bail|string|required|min:8|max:8',
    'pos' => 'int|required|max:1',
    'web' => 'int|required|max:1',
    'android' => 'int|required|max:1',
    'android_pos' => 'int|required|max:1'
  ]);

  // check if wallet id already exists in database
  $wallet_id = $request->wallet_id;
  $checkwallet_id = Wallet::where('wallet_id', $wallet_id)->exists();
  if($checkwallet_id === true){
    return response()->json([
      'msg' => 'wallet_id already exists.',
    ]);
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
 

 /**
 * update a wallet.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return void
 */
//  public function updateWallet(Request $request, $id){
//   // $wallet_id = $id;
//   $wallet = Wallet::findOrFail($id);
//   $wallet->wallet_id = $request->wallet_id;
//   $wallet->pos = $request->pos;
//   $wallet->web = $request->web;
//   $wallet->android = $request->android;
//   $wallet->android_pos = $request->android_pos;
//   $wallet->save();

//   // return value
//   return response()->json($wallet, 200);
//  }

 
}