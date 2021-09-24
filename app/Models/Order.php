<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['amount', 'type', 'item', 'state', 'user_id', 'report_id', 'report_state'];
}
