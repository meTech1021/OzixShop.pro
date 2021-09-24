<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = ['acctype', 'country', 'country_full', 'infos', 'url', 'screenshot', 'price', 'seller_id', 'sold', 'sto', 'sold_date', 'number', 'reported'];
}
