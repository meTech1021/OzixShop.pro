<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resseller extends Model
{
    protected $fillable = ['user_id', 'sold_btc', 'unsold_btc', 'item_sold_btc', 'item_unsold_btc', 'activate', 'btc_address', 'withdrawal', 'all_sales', 'last_week'];
}
