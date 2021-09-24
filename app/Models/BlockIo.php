<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Blockavel\LaraBlockIo\LaraBlockIo;

class BlockIo extends Model
{
    /**
     * Get the balance information associated with a Bitcoin Dogecoin,
     * or Litecoin account.
     *
    * @return object Contains balance information
    */

   public static function CreateAddressBtc($label)
   {
    //    die(print_r('dddddd'));
       return LaraBlockIo::createAddress($label);
   }
}
