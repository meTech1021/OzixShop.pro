<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coinpayments extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'coinpayments';

    /**
     * The table primary key. Eloquent will also assume that each table has a primary key column named id. 
     * You may define a protected $primaryKey property to override this convention.
     *
     * @var bool
     */
    public $primaryKey = 'id'; 
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true; 
}
