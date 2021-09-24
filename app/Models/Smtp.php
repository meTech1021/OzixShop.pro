<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Smtp extends Model
{
    protected $fillable = ['acctype', 'country', 'country_full', 'infos', 'price', 'url', 'sold', 'sold_date', 'sto', 'seller_id', 'reported', 'source'];
}
