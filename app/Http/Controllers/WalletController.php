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
      'wallet_id' => 'required|string|min:8|max:8',
      'channel' => 'required|string',
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
      $wallet = Wallet::where('wallet_id', $wallet_id)->first();
      // print_r($wallet);
      $json = json_encode($wallet);
      $phpArray = json_decode($json, true);
      return $phpArray;
  }

  public function check_for_restricted_wallet($wallet_details, $channel){

    //print_r($wallet_details);
    // $storedChannels = array_keys($wallet_details);
    array_shift($wallet_details);
    array_pop($wallet_details);
    array_pop($wallet_details);
    // print_r($wallet_details);
    
    
   // array_key_exists('errcode', $phpArray);
   // in_array($channel, $channels)
    // $channels = ['pos', 'web', 'android', 'android_pos'];
    if(array_key_exists($channel, $wallet_details)){
      if($wallet_details[$channel] == 0){
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
      'wallet_id' => 'required|string|min:8|max:8',
      'pos' => 'required|int|max:1',
      'web' => 'required|int|max:1',
      'android' => 'required|int|max:1',
      'android_pos' => 'required|int|max:1',
      'update' => 'required|boolean'
    ]);

    $update = $request->update;
    if($update){
      $wallet_id = $request->wallet_id;
      $wallet_update = Wallet::where('wallet_id', $wallet_id)->first();
      $wallet_update->wallet_id = $wallet_id;
      $wallet_update->pos = $request->pos;
      $wallet_update->web = $request->web;
      $wallet_update->android = $request->android;
      $wallet_update->android_pos = $request->android_pos;
      $wallet_update->save();

      // return value
      return response()->json($wallet_update, 200);
    }else{
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