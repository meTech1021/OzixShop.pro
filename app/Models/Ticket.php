<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['user', 'user_id', 'status', 's_id', 's_url', 'memo', 'acctype', 'admin_r', 'subject', 'type', 'seller_id', 'price', 'refunded', 'fmemo', 'last_reply', 'seen'];
}
