<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = ['s_id', 'buyer', 'item_id', 'type', 'country', 'country_full', 'infos', 'url', 'login', 'pass', 'price', 'seller_id', 'reported', 'report_id'];
}
