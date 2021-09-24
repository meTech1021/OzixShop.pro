<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    protected $fillable = ['user_id', 'btc_address', 'btc_amount', 'usd_amount', 'fee', 'btc_rate', 'percentage', 'hash', 'state'];
}
