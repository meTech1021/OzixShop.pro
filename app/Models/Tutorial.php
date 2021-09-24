<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutorial extends Model
{
    protected $fillable = ['acctype', 'country', 'country_full', 'infos', 'url', 'tutorial_name', 'price', 'sold', 'sold_date', 'sto', 'seller_id', 'screenshot'];
}
