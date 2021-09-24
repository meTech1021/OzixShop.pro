<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rpayment extends Model
{
    protected $fillable = ['username', 'amount', 'amount_btc', 'btc_address', 'method', 'url', 'urid', 'rate', 'fee'];
}
