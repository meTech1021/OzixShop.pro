<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mailer extends Model
{
    protected $fillable = ['acctype', 'country', 'country_full', 'infos', 'url', 'price', 'seller_id', 'sold', 'sold_date', 'sto', 'reported', 'source', 'ssl_status'];
}
