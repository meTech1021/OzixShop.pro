<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rdp extends Model
{
    protected $fillable = ['acctype', 'country', 'country_full', 'city', 'infos', 'price', 'url', 'sold', 'sto', 'sold_date', 'access', 'windows', 'ram', 'source', 'seller_id', 'reported'];
}
