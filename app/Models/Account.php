<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['acctype', 'country', 'country_full', 'infos', 'price', 'url', 'sold', 'sto', 'sold_date', 'seller_id', 'reported', 'sitename', 'screenshot', 'source'];
}
