<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scam extends Model
{
    protected $fillable = ['acctype', 'country', 'country_full', 'infos', 'url', 'scam_name', 'price', 'sold', 'sold_date', 'sto', 'seller_id', 'screenshot'];
}
