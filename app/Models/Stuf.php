<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stuf extends Model
{
    protected $fillable = ['acctype', 'country', 'country_full', 'infos', 'url', 'price', 'seller_id', 'sold', 'sold_date', 'reported', 'sto', 'hosting_detect', 'ssl_status', 'source', 'domain'];
}
