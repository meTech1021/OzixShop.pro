<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['user', 'user_id', 'status', 's_id', 'url', 'memo', 'acctype', 'admin_r', 'subject', 'type', 'seller_id', 'price', 'refunded', 'fmemo', 'last_reply', 's_info', 'seen', 'order_id', 'lastup', 'state'];
}
