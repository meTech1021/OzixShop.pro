<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Cpanel;
use App\Models\Lead;
use App\Models\Mailer;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Payment;
use App\Models\Rdp;
use App\Models\Report;
use App\Models\Resseller;
use App\Models\Scam;
use App\Models\Smtp;
use App\Models\Stuf;
use App\Models\Ticket;
use App\Models\Tutorial;
use Illuminate\Support\Facades\Auth;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Coinpayments;
use App\Lib\CoinPaymentsAPI;

class HomeController extends Controller
{
    public function index()
    {
        $news = News::where('type', 2)->orderBy('created_at', 'desc')->limit(5)->get();
        $rdps = Rdp::where('sold', 0)->count();
        $shells = Stuf::where('sold', 0)->count();
        $cpanels = Cpanel::where('sold', 0)->count();
        $mailers = Mailer::where('sold', 0)->count();
        $smtps = Smtp::where('sold', 0)->count();
        $leads = Lead::where('sold', 0)->count();
        $accounts = Account::where('sold', 0)->count();
        $scams = Scam::where('sold', 0)->count();
        $tutorials = Tutorial::where('sold', 0)->count();
        if(Auth::user()->role == 2) {
            $seller = Resseller::where('id', Auth::user()->seller_id)->first();
            $balance = $seller->sold_btc;
        } else {
            $balance = Auth::user()->balance;
        }
        $orders = Purchase::where('s_id', Auth::user()->id)->count();
        $report_cnt = Report::where('user_id', Auth::user()->id)->count();
        $ticket_cnt = Ticket::where('user_id', Auth::user()->id)->count();
        return view('pages.buyer.dashboard')->with([
            'news' => $news,
            'rdps' => $rdps,
            'shells' => $shells,
            'cpanels' => $cpanels,
            'mailers' => $mailers,
            'smtps' => $smtps,
            'leads' => $leads,
            'accounts' => $accounts,
            'scams' => $scams,
            'tutorials' => $tutorials,
            'balance' => $balance,
            'orders' => $orders,
            'report_cnt' => $report_cnt,
            'ticket_cnt' => $ticket_cnt
        ]);
    }

    public function get_infos(Request $request)
    {
        $report_cnt = Report::where('user_id', Auth::user()->id)->count();
        $ticket_cnt = Ticket::where('user_id', Auth::user()->id)->count();

        if(Auth::user()->role == 2) {
            $seller = Resseller::where('id', Auth::user()->seller_id)->first();
            $balance = $seller->sold_btc;
        } else {
            $balance = Auth::user()->balance;
        }

        $rdps_cnt = Rdp::where('sold', 0)->count();
        $shells_cnt = Stuf::where('sold', 0)->count();
        $cpanels_cnt = Cpanel::where('sold', 0)->count();
        $checked_list_cnt = Lead::where('sold', 0)->where('acctype', '100% Checked Email List')->count();
        $email_list_cnt = Lead::where('sold', 0)->where('acctype', 'Email List')->count();
        $combo_list_cnt = Lead::where('sold', 0)->where('acctype', 'Combo List')->count();
        $mailers_cnt = Mailer::where('sold', 0)->count();
        $smtps_cnt = Smtp::where('sold', 0)->count();
        $marketings_cnt = Account::where('sold', 0)->where('acctype', 'Marketing')->count();
        $hostings_cnt = Account::where('sold', 0)->where('acctype', 'Hosting/Domain')->count();
        $games_cnt = Account::where('sold', 0)->where('acctype', 'Games')->count();
        $vpns_cnt = Account::where('sold', 0)->where('acctype', 'VPN/Socks Proxy')->count();
        $shoppings_cnt = Account::where('sold', 0)->where('acctype', 'Shopping{Amazon, Ebay, .... etc}')->count();
        $streams_cnt = Account::where('sold', 0)->where('acctype', 'Stream { Music, Netflix, iptv, HBO, bein sport, WWE ...etc }')->count();
        $datings_cnt = Account::where('sold', 0)->where('acctype', 'Dating')->count();
        $learnings_cnt = Account::where('sold', 0)->where('acctype', 'Learning { udemy, lynda, .... etc. }')->count();
        $voips_cnt = Account::where('sold', 0)->where('acctype', 'Voip/Sip')->count();
        $scams_cnt = Scam::where('sold', 0)->count();
        $tutorials_cnt = Tutorial::where('sold', 0)->count();

        return response()->json([
            'report_cnt' => $report_cnt,
            'ticket_cnt' => $ticket_cnt,
            'balance' => $balance,
            'rdps_cnt' => $rdps_cnt,
            'shells_cnt' => $shells_cnt,
            'cpanels_cnt' => $cpanels_cnt,
            'checked_list_cnt' => $checked_list_cnt,
            'email_list_cnt' => $email_list_cnt,
            'combo_list_cnt' => $combo_list_cnt,
            'mailers_cnt' => $mailers_cnt,
            'smtps_cnt' => $smtps_cnt,
            'marketings_cnt' => $marketings_cnt,
            'hostings_cnt' => $hostings_cnt,
            'games_cnt' => $games_cnt,
            'vpns_cnt' => $vpns_cnt,
            'shoppings_cnt' => $shoppings_cnt,
            'streams_cnt' => $streams_cnt,
            'datings_cnt' => $datings_cnt,
            'learnings_cnt' => $learnings_cnt,
            'voips_cnt' => $voips_cnt,
            'scams_cnt' => $scams_cnt,
            'tutorials_cnt' => $tutorials_cnt
        ]);
    }

    public function ticket()
    {
        $tickets = Ticket::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();

        return view('pages.buyer.ticket')->with([
            'tickets' => $tickets
        ]);
    }

    public function get_ticket(Request $request)
    {
        $ticket_id = $request->ticket_id;

        $ticket = Ticket::where('id', $ticket_id)->first();
        return response()->json([
            'ticket' => $ticket
        ]);
    }

    public function ticket_save(Request $request)
    {
        $title = $request->title;
        $message = $request->message;

        $message = '<div class="panel panel-default" style="border : 1px solid #00bbb1!important;">
        <div class="panel-body"><b>
      '.$message.'</b>
       </div>
        <div class="panel-footer"><div class="label label-primary">' . Auth::user()->name . '</div> - '.date("d/m/Y h:i:s a").'</div>
        </div>';

        Ticket::create([
            'user_id' => Auth::user()->id,
            'user' => Auth::user()->name,
            'status' => 1,
            'memo' => $message,
            'acctype' => null,
            'admin_r' => '0',
            'subject' => $title,
            'type' => 'refunding',
            'seller_id' => null,
            'price' => 1,
            'refunded' => 'Not Yet !',
            'fmemo' => $message,
            'seen' => 0,
            'last_reply' => Auth::user()->name,
        ]);

        return redirect('/ticket');
    }

    public function ticket_reply(Request $request)
    {
        $ticket_id = $request->ticket_id;
        $msg = $request->message;

        $ticket = Ticket::where('id', $ticket_id)->first();
        $message = $ticket->memo.'<div class="panel panel-default" style="border : 1px solid #00bbb1!important;">
        <div class="panel-body">
      <b>'.$msg.'</b>
       </div>
        <div class="panel-footer"><div class="label label-primary">' . Auth::user()->name . '</div> - '.date("d/m/Y h:i:s").'</div>
        </div>';
        Ticket::where('id', $ticket_id)->update([
            'memo' => $message,
            'status' => 1,
            'seen' => 1,
            'last_reply' => Auth::user()->name
        ]);

        $date = date("d/m/Y h:i:s");

        return response()->json([
            'msg' => 'success',
            'date' => $date,
        ]);
    }

    public function ticket_close(Request $request)
    {
        Ticket::where('id', $request->ticket_id)->update([
            'status' => 0
        ]);

        return response()->json([
            'msg' => 'closed'
        ]);
    }

    public function balance()
    {
        $payments = Payment::where('user_id', Auth::user()->id)->get();
        return view('pages.buyer.balance')->with([
            'payments'     => $payments
        ]);
    }

    public function balance_save(Request $request)
    {
        $method = $request->method;
        $amount = $request->amount;
        $user = Auth::user();

        if($method == 'BitcoinPayment') {
            
            // Coinpayments API 
            
            $private_key = config('services.coinpayments.private_key');
            $public_key = config('services.coinpayments.public_key');
                
            try {  
                
                $cps = new CoinpaymentsAPI();
                $cps->Setup($private_key, $public_key);
                
                $result = $cps->CreateTransaction([
                    'amount' => $amount,
                    'currency1' => 'USD',
                    'currency2' => 'BTC',
                    'buyer_email' => Auth::user()->email,
                    'custom' => Auth::id(), 
                    'address' => '', // leave blank send to follow your settings on the Coin Settings page
                    'item_name' => 'Add Fund',
                    'ipn_url' => url('ipn/deposit/coinpayments'),
                ]);
                
                if(strtolower($result['error'])=='ok')
                {
                    $order = $result['result'];

                    $created_at = date('Y-m-d H:i:s');
                    $expire_at = strtotime('+ '.$order['timeout'].' seconds', strtotime($created_at));

                    $coinpayments = new Coinpayments;
                    $coinpayments->user_id = Auth::id();
                    $coinpayments->amount = $amount;
                    $coinpayments->coin_title = 'Bitcoin';
                    $coinpayments->coin_amount = $order['amount'];
                    $coinpayments->coin_currency = 'BTC';
                    $coinpayments->coin_address = $order['address'];
                    $coinpayments->txn_id = $order['txn_id'];
                    $coinpayments->status = 0;
                    $coinpayments->status_text = 'Waiting for buyer funds';
                    $coinpayments->timeout_seconds = $order['timeout'];
                    $coinpayments->expire_at = date('Y-m-d H:i:s', $expire_at);
                    $coinpayments->qrcode_url = $order['qrcode_url'];
                    $coinpayments->status_url = $order['status_url'];
                    $coinpayments->save();

                    $id = $coinpayments->id;

                    return redirect('coinpayments/details/'.$id);
                }
                else
                {
                    return back()->with('error', $result['error']);
                }
                
            } catch (Exception $e) {
                
                return back()->with('error', $e->getMessage());
            }


        } else {
            $url_btc = 'https://blockchain.info/ticker';
            $response_btc = file_get_contents($url_btc);
            $object_btc = json_decode($response_btc);
            $usdprice = $object_btc->{"USD"}->{"last"};
            $rate['rate'] =  $object_btc->{"USD"}->{"last"};
            $rate = $rate['rate'];
            $btcamount = number_format($amount/$rate, 8, '.', '');
            $btcamm = $btcamount;

            $guid = 'Email';  // Block.io account
            $main_password = ''; // Block.io Password
            $pin = ''; // Block.io PIN
            $apikey = "8948-b3ca-aa60-b493"; // block.io API KEY
            $block_io = new BlockIo($apikey, $pin, 2);
            $new_address = $block_io->get_new_address();

            $payment = Payment::create([
                'user_id' => $user_id,
                'method' => $method,
                'amount' => $btcamm,
                'amount_usd' => $amount,
                'address' => $new_address,
                'state' => 'pending'
            ]);

        }


        return view('pages.buyer.payment_state')->with([
            'payment' => $payment,
            'rate' => $rate
        ]);
    }
    
    public function coinpayments($id, Request $request)
    {
        $data = [];
        
        $order = Coinpayments::where('user_id', Auth::id())->where('id', $id)->first();
        
        if(empty($order))
        {
            return redirect('balance')->with('error', 'You are not authorized to view this page');    
        }
        
        if(strtotime($order->expire_at) > time())
        {
            $remaining_seconds = (strtotime($order->expire_at) - time());
        }
        else
        {
            $remaining_seconds = 0;
        }
            
        $data['order'] = $order;
        $data['remaining_seconds'] = $remaining_seconds;
        
        $url_btc = 'https://blockchain.info/ticker';
        $response_btc = file_get_contents($url_btc);
        $object_btc = json_decode($response_btc);
        $usdprice = $object_btc->{"USD"}->{"last"};
        $data['btc_rate'] = $usdprice;
        
        return view('pages.buyer.coinpayments', $data);
    }
    
    public function coinpayments_status(Request $request)
    {
        $txn_id = $request->txn_id;

        $order = Coinpayments::where('user_id', Auth::id())
        ->where('txn_id', $request->txn_id)
        ->first();
        
        if(!empty($order->id))
        {
            return response()->json(array('order_status'=>$order->status, 'status_text'=>$order->status_text));
        }
        else
        {
            return response()->json(array('order_status'=>0, 'status_text'=>'Invalid ID'));
        }
    }

    public function orders()
    {
        $purchases = Purchase::where('s_id', Auth::user()->id)
                        ->orderBy('id', 'desc')->get();

        return view('pages.buyer.order')->with([
            'purchases' => $purchases
        ]);
    }

    public function get_order(Request $request)
    {
        $purchase_id = $request->purchase_id;
        $purchase = Purchase::where('id', $purchase_id)->first();

        return response()->json([
            'purchase' => $purchase
        ]);
    }

    public function get_report(Request $request)
    {
        $report_id = $request->report_id;

        $report = Report::where('id', $report_id)->first();

        return response()->json([
            'report' => $report
        ]);
    }

    public function report_save(Request $request)
    {
        $msg = $request->message;
        $report_id = $request->report_id;
        $date = date("d/m/Y h:i:s");
        $purchase_id = $request->purchase_id;
        $purchase = Purchase::where('id', $purchase_id)->where('s_id', Auth::user()->id)->first();
        // die(print_r($purchase->item_id));
        if($report_id == '') {
            $msg = '<div class="panel panel-default" style="border : 1px solid #00bbb1!important;"><div class="panel-body"><div class="report"><b>'.htmlspecialchars($msg).'</b></div></div><div class="panel-footer"><div class="label label-primary">'.Auth::user()->name.'</div> - '.$date.'</div></div>';
            // die(print_r($msg));
            $report = Report::create([
                'user_id' => Auth::user()->id,
                'user' => Auth::user()->name,
                's_id' => $purchase->item_id,
                'url' => $purchase->url,
                'acctype' => $purchase->type,
                'admin_r' => 0,
                'type' => 'request',
                'seller_id' => $purchase->seller_id,
                'price' => $purchase->price,
                'refunded' => 'Not Yet !',
                'fmemo' => $msg,
                's_info' => $purchase->infos,
                'order_id' => $purchase->id,
                'memo' => $msg,
                'state' => 'onHold',
                'status' => 1,
                'seen' => 1,
                'last_reply' => Auth::user()->name
            ]);
            Purchase::where('id', $purchase_id)->update([
                'reported' => 1,
                'report_id' => $report->id,
            ]);
            return response()->json([
                'msg' => 'updated',
                'date' => $date,
                'report' => $report
            ]);
        } else {
            $msg = '<div class="panel panel-default" style="border : 1px solid #00bbb1!important;"><div class="panel-body"><div class="report"><b>'.htmlspecialchars($msg).'</b></div></div><div class="panel-footer"><div class="label label-primary">'.Auth::user()->name.'</div> - '.$date.'</div></div>';
            // die(print_r($msg));
            $report = Report::where('id', $report_id)->first();
            $new_memo = $report->memo.' '.$msg;
            Report::where('id', $report_id)->update([
                'memo' => $new_memo,
                'status' => 1,
                'seen' => 1,
                'last_reply' => Auth::user()->name
            ]);
            return response()->json([
                'msg' => 'updated',
                'date' => $date
            ]);
        }
    }

    public function reports()
    {
        $reports = Report::where('user_id', Auth::user()->id)
                        ->orderBy('id', 'desc')->get();

        return view('pages.buyer.report')->with([
            'reports' => $reports
        ]);
    }
    
    
    public function report_close(Request $request)
    {
        $report_id = $request->report_id;

        Report::where('id', $report_id)->update([
            'status' => 0
        ]);

        return response()->json([
            'msg' => 'closed'
        ]);
    }

    public function setting()
    {
        return view('pages.buyer.setting');
    }

    public function setting_save(Request $request)
    {

        if(!Hash::check($request->current_password, Auth::user()->password)) {
            $msg = 'Incorrect current password';
        } else {
            $user = User::where('email', $request->email)->count();
            if($user == 0) {
                if($request->password == $request->password_confirmation) {
                    User::where('id', Auth::user()->id)->update([
                        'email' => $request->email,
                        'password' => bcrypt($request->password)
                    ]);
                    $msg = 'Successfully changed';
                } else {
                    $msg = 'New password is not equal to confirm password';
                }
            } else {
                $msg = 'This email already exists';
            }
        }

        return response()->json([
            'msg' => $msg
        ]);
    }
}
