<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['user_id', 'method', 'amount', 'amount_usd', 'address', 'p_data', 'state'];
}
