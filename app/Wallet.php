<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model{
    protected $table = 'wallet';
    protected $primaryKey = 'wallet_id';
    public $incrementing = false;
    protected $keyType = 'string';
}