<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cpanel extends Model
{
    protected $fillable = ['acctype', 'country', 'country_full', 'infos', 'url', 'price', 'sold', 'sto', 'sold_date', 'seller_id', 'reported', 'ssl_status', 'source'];
}
