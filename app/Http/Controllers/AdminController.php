<?php
namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Order;
use App\Models\Purchase;
use App\Models\Report;
use App\Models\Resseller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Coindesk;
use App\BlockIo;
use App\Models\Account;
use App\Models\Cpanel;
use App\Models\Lead;
use App\Models\Mailer;
use App\Models\Rdp;
use App\Models\Rpayment;
use App\Models\Scam;
use App\Models\Smtp;
use App\Models\Stuf;
use App\Models\Tutorial;

class AdminController extends Controller
{
    public function index()
    {
        $tickets_cnt = Ticket::where('status', 1)->orWhere('status', 2)->count();
        $users_cnt = User::count();
        $reports_cnt = Report::where('status', 1)->orWhere('status', 2)->count();
        $sellers_cnt = Resseller::count();
        $recent_tickets = Ticket::limit(5)->get();
        $recent_users = User::orderBy('id', 'desc')->limit(5)->get();

        $firstDay = date('Y-m-d');
        $sales_array = array();
        $users_array = array();
        for($i = 0 ; $i < 7 ; $i ++) {
            $firstDay = date('Y-m-d', strtotime('-'.$i.' days'));
            $secondDay = date('Y-m-d', strtotime('-'.($i+1).' days'));
            $purchases = Purchase::whereBetween('created_at', [$secondDay.' 00:00:00', $firstDay.' 00:00:00'])->get();
            $user_cnt = User::whereBetween('created_at', [$firstDay.' 00:00:00', $firstDay.' 23:59:59'])->count();
            $sales = 0;
            foreach($purchases as $purchase) {
                $sales += $purchase->price;
            }

            $day = '20'.date('y-m-d', strtotime('-'.($i+1).' days'));
            if($i == 0) {
                $day = 'Yesterday';
            }

            $each_sale = array([
                'day' => $day,
                'sale' => $sales
            ]);

            $user_day = '20'.date('y-m-d', strtotime('-'.$i.' days'));
            if($i == 0) {
                $user_day = 'Today';
            } elseif($i == 1) {
                $user_day = 'Yesterday';
            }

            $each_day = array([
                'day' => $user_day,
                'users' => $user_cnt
            ]);

            array_push($users_array, $each_day);
            array_push($sales_array, $each_sale);
        }
        // die(print_r($sales_array));
        $sales_array = json_encode($sales_array);
        $users_array = json_encode($users_array);

        return view('pages.admin.dashboard')->with([
            'tickets_cnt' => $tickets_cnt,
            'users_cnt' => $users_cnt,
            'reports_cnt' => $reports_cnt,
            'sellers_cnt' => $sellers_cnt,
            'recent_tickets' => $recent_tickets,
            'recent_users' => $recent_users,
            'sales' => $sales_array,
            'users' => $users_array
        ]);
    }

    public function financial()
    {
        $total = Order::sum('amount');
        $todayTotal = Order::where('created_at', 'like', '20'.date('y-m-d').'%')->sum('amount');
        $monthTotal = Order::where('created_at', 'like', '20'.date('y-m-').'%')->sum('amount');

        return view('pages.admin.financial')->with([
            'total' => $total,
            'todayTotal' => $todayTotal,
            'monthTotal' => $monthTotal
        ]);
    }

    public function orders()
    {
        $sales = Purchase::all();
        $total_sales = 0;
        foreach($sales as $sale) {
            $total_sales += $sale->price;
        }

        $last_sales = Purchase::limit(300)->get();
        $new_sales_array = array();

        foreach($last_sales as $sale) {
            $report_status = Report::where('order_id', $sale->id)->first();
            $new_sale = [
                'id' => $sale->id,
                'buyer' => $sale->buyer,
                'type' => $sale->type,
                'price' => $sale->price,
                'seller_id' => $sale->seller_id,
                'report_id' => $sale->report_id,
                'created_at' => $sale->created_at,
                'report_status' => $report_status
            ];

            array_push($new_sales_array, $new_sale);
        }

        return view('pages.admin.sales')->with([
            'sales' => $sales,
            'total_sales' => $total_sales,
            'last_sales' => $last_sales
        ]);
    }

    public function news()
    {
        $seller_news = News::where('type', 1)->get();
        $buyer_news = News::where('type', 2)->get();

        return view('pages.admin.news')->with([
            'seller_news' => $seller_news,
            'buyer_news' => $buyer_news
        ]);
    }

    public function news_save(Request $request)
    {
        $news = News::create([
            'type' => $request->type,
            'title' => $request->title,
            'content' => $request->content
        ]);

        return response()->json([
            'msg' => 'success',
            'news' => $news
        ]);
    }

    public function news_delete(Request $request)
    {
        News::where('id', $request->news_id)->delete();

        return response()->json([
            'msg' => 'deleted'
        ]);
    }

    public function tools()
    {
        $rdps = DB::table('users')->join('rdps', 'users.seller_id', '=', 'rdps.seller_id')
                    ->select('users.name as sellername', 'rdps.*')
                    ->get();

        $shells = DB::table('users')->join('stufs', 'users.seller_id', '=', 'stufs.seller_id')
                    ->select('users.name as sellername', 'stufs.*')
                    ->get();

        $cpanels = DB::table('users')->join('cpanels', 'users.seller_id', '=', 'cpanels.seller_id')
                    ->select('users.name as sellername', 'cpanels.*')
                    ->get();

        $mailers = DB::table('users')->join('mailers', 'users.seller_id', '=', 'mailers.seller_id')
                    ->select('users.name as sellername', 'mailers.*')
                    ->get();

        $smtps = DB::table('users')->join('smtps', 'users.seller_id', '=', 'smtps.seller_id')
                    ->select('users.name as sellername', 'smtps.*')
                    ->get();

        $leads = DB::table('users')->join('leads', 'users.seller_id', '=', 'leads.seller_id')
                    ->select('users.name as sellername', 'leads.*')
                    ->get();

        $accounts = DB::table('users')->join('accounts', 'users.seller_id', '=', 'accounts.seller_id')
                    ->select('users.name as sellername', 'accounts.*')
                    ->get();

        $scams = DB::table('users')->join('scams', 'users.seller_id', '=', 'scams.seller_id')
                    ->select('users.name as sellername', 'scams.*')
                    ->get();

        $tutorials = DB::table('users')->join('tutorials', 'users.seller_id', '=', 'tutorials.seller_id')
                    ->select('users.name as sellername', 'tutorials.*')
                    ->get();

        return view('pages.admin.tools')->with([
            'rdps' => $rdps,
            'shells' => $shells,
            'cpanels' => $cpanels,
            'mailers' => $mailers,
            'smtps' => $smtps,
            'leads' => $leads,
            'accounts' => $accounts,
            'scams' => $scams,
            'tutorials'=> $tutorials
        ]);
    }

    public function tickets()
    {
        $tickets = Ticket::where('status', '>', 0)->orderBy('status', 'desc')->get();
        $users = User::where('role', 3)->get();
        return view('pages.admin.tickets')->with([
            'tickets' => $tickets,
            'users' => $users
        ]);
    }

    public function ticket_insert(Request $request)
    {
        $title = $request->title;
        $username = $request->username;
        $user = User::where('name', $username)->first();

        $ticket = Ticket::create([
            'user' => $username,
            'user_id' => $user->id,
            'status' => 1,
            's_id' => 1,
            's_url' => '1',
            'memo' => null,
            'acctype' => '1',
            'admin_r' => '0',
            'subject' => $title,
            'type' => 'refunding',
            'seller_id' => 1,
            'price' => 1,
            'refunded' => 'Not Yet',
            'fmemo' => null,
            'seen' => 1,
            'last_reply' => 'Admin',
        ]);

        return response()->json([
            'msg' => 'success',
            'ticket' => $ticket
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

    public function reply(Request $request)
    {
        $msg = $request->message;
        $ticket_id = $request->ticket_id;
        $date = date("d/m/Y h:i:s");
        $msg = '<div class="panel panel-default" style="border : 1px solid #00bbb1!important;"><div class="panel-body"><div class="ticket"><b>'.htmlspecialchars($msg).'</b></div></div><div class="panel-footer"><div class="label label-danger">Admin</div> - '.$date.'</div></div>';
        // die(print_r($msg));
        $ticket = Ticket::where('id', $ticket_id)->first();
        $new_memo = $ticket->memo.' '.$msg;
        Ticket::where('id', $ticket_id)->update([
            'memo' => $new_memo,
            'status' => 1,
            'seen' => 1,
            'last_reply' => 'Admin'
        ]);

        return response()->json([
            'msg' => 'updated',
            'date' => $date
        ]);
    }

    public function close(Request $request)
    {
        Ticket::where('id', $request->ticket_id)->update([
            'status' => 0
        ]);

        return response()->json([
            'msg' => 'closed'
        ]);
    }

    public function reports()
    {
        $reports = DB::table('users')->join('reports', 'users.seller_id', '=', 'reports.seller_id')
                    ->where('reports.status', 1)
                    ->select('reports.*', 'users.name as sellername')->get();

        return view('pages.admin.reports')->with([
            'reports' => $reports
        ]);
    }

    public function report_view($id)
    {
        $report = Report::where('id', $id)->first();

        if($report->acctype == 'cPanel') {
            $cpanel = Cpanel::where('id', $report->s_id)->first();
            return view('pages.admin.report_view')->with([
                'report' => $report,
                'cpanel' => $cpanel
            ]);
        } elseif($report->acctype == 'Shell') {
            $shell = Stuf::where('id', $report->s_id)->first();
            return view('pages.admin.report_view')->with([
                'report' => $report,
                'shell' => $shell
            ]);
        } elseif($report->acctype == 'RDP') {
            $rdp = Rdp::where('id', $report->s_id)->first();
            return view('pages.admin.report_view')->with([
                'report' => $report,
                'rdp' => $rdp
            ]);
        } elseif($report->acctype == 'Mailer') {
            $mailer = Mailer::where('id', $report->s_id)->first();
            return view('pages.admin.report_view')->with([
                'report' => $report,
                'mailer' => $mailer
            ]);
        } elseif($report->acctype == 'SMTP') {
            $smtp = Smtp::where('id', $report->s_id)->first();
            return view('pages.admin.report_view')->with([
                'report' => $report,
                'smtp' => $smtp
            ]);
        } elseif($report->acctype == 'Email List') {
            $email_list = Lead::where('id', $report->s_id)->first();
            return view('pages.admin.report_view')->with([
                'report' => $report,
                'email_list' => $email_list
            ]);
        } elseif($report->acctype == 'Combo List') {
            $combo_list = Lead::where('id', $report->s_id)->first();
            return view('pages.admin.report_view')->with([
                'report' => $report,
                'combo_list' => $combo_list
            ]);
        } elseif($report->acctype == '100% Email Checked List') {
            $checked_list = Lead::where('id', $report->s_id)->first();
            return view('pages.admin.report_view')->with([
                'report' => $report,
                'checked_list' => $checked_list
            ]);
        } elseif($report->acctype == 'Hosting/Domain') {
            $hosting = Account::where('id', $report->s_id)->first();
            return view('pages.admin.report_view')->with([
                'report' => $report,
                'hosting' => $hosting
            ]);
        } elseif($report->acctype == 'Marketing') {
            $marketing = Account::where('id', $report->s_id)->first();
            return view('pages.admin.report_view')->with([
                'report' => $report,
                'marketing' => $marketing
            ]);
        } elseif($report->acctype == 'Games') {
            $game = Account::where('id', $report->s_id)->first();
            return view('pages.admin.report_view')->with([
                'report' => $report,
                'game' => $game
            ]);
        } elseif($report->acctype == 'VPN/Socks Proxy') {
            $vpn = Account::where('id', $report->s_id)->first();
            return view('pages.admin.report_view')->with([
                'report' => $report,
                'vpn' => $vpn
            ]);
        } elseif($report->acctype == 'Shopping {Amazon, eBay .... etc }') {
            $shopping = Account::where('id', $report->s_id)->first();
            return view('pages.admin.report_view')->with([
                'report' => $report,
                'shopping' => $shopping
            ]);
        } elseif($report->acctype == 'Stream { Music, Netflix, iptv, HBO, bein sport, WWE ...etc }') {
            $stream = Account::where('id', $report->s_id)->first();
            return view('pages.admin.report_view')->with([
                'report' => $report,
                'stream' => $stream
            ]);
        } elseif($report->acctype == 'Dating') {
            $dating = Account::where('id', $report->s_id)->first();
            return view('pages.admin.report_view')->with([
                'report' => $report,
                'dating' => $dating
            ]);
        } elseif($report->acctype == 'Voip/Sip') {
            $voip = Account::where('id', $report->s_id)->first();
            return view('pages.admin.report_view')->with([
                'report' => $report,
                'voip' => $voip
            ]);
        } elseif($report->acctype == 'Learning { udemy, lynda, .... etc. }') {
            $learning = Account::where('id', $report->s_id)->first();
            return view('pages.admin.report_view')->with([
                'report' => $report,
                'learning' => $learning
            ]);
        } elseif($report->acctype == 'Exploit/Script/ScamPage') {
            $scam = Scam::where('id', $report->s_id)->first();
            return view('pages.admin.report_view')->with([
                'report' => $report,
                'scam' => $scam
            ]);
        } elseif($report->acctype == 'Tutorial / Method') {
            $tutorial = Tutorial::where('id', $report->s_id)->first();
            return view('pages.admin.report_view')->with([
                'report' => $report,
                'tutorial' => $tutorial
            ]);
        }
    }

    public function report_reply(Request $request)
    {
        $msg = $request->message;
        $report_id = $request->report_id;
        $date = date("d/m/Y h:i:s");
        $msg = '<div class="panel panel-default" style="border : 1px solid #00bbb1!important;"><div class="panel-body"><div class="report"><b>'.htmlspecialchars($msg).'</b></div></div><div class="panel-footer"><div class="label label-danger">Admin</div> - '.$date.'</div></div>';
        // die(print_r($msg));
        $report = Report::where('id', $report_id)->first();
        $new_memo = $report->memo.' '.$msg;
        Report::where('id', $report_id)->update([
            'memo' => $new_memo,
            'status' => 1,
            'seen' => 1,
            'last_reply' => 'Admin'
        ]);

        return response()->json([
            'msg' => 'updated',
            'date' => $date
        ]);
    }

    public function refund(Request $request)
    {
        $report_id = $request->report_id;
        Report::where('id', $report_id)->update([
            'refunded' => 'Refunded',
            'status' => 0
        ]);

        $report = Report::where('id', $report_id)->first();
        $user = User::where('id', $report->user_id)->first();
        $balance = $user->balance + $report->price;
        User::where('id', $report->user_id)->update([
            'balance' => $balance
        ]);

        $seller = Resseller::where('id', $report->seller_id)->first();
        $sold_btc = $seller->sold_btc - $report->price;
        Resseller::where('id', $report->seller_id)->update([
            'sold_btc' => $sold_btc
        ]);

        return response()->json([
            'msg' => 'success'
        ]);
    }

    public function users()
    {
        $users = User::all();

        return view('pages.admin.users')->with([
            'users' => $users
        ]);
    }

    public function make_seller(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::where('id', $user_id)->first();

        $seller = Resseller::create([
            'user_id' => $user_id
        ]);

        User::where('id', $user_id)->update([
            'role' => 2,
            'seller_id' => $seller->id
        ]);

        return response()->json([
            'msg' => 'success'
        ]);
    }

    public function sellers()
    {
        $sellers = DB::table('users')->join('ressellers', 'users.seller_id', '=', 'ressellers.id')
                    ->select('users.name as sellername', 'ressellers.*')
                    ->get();

        return view('pages.admin.sellers')->with([
            'sellers' => $sellers
        ]);
    }

    public function get(Request $request)
    {
        $seller = DB::table('users')->join('ressellers', 'users.seller_id', '=', 'ressellers.id')
                    ->where('ressellers.id', $request->seller_id)
                    ->select('users.name as sellername', 'ressellers.*')
                    ->first();

        return response()->json([
            'seller' => $seller
        ]);
    }

    public function save(Request $request)
    {
        Resseller::where('id', $request->seller_id)->update([
            'sold_btc' => $request->sold_balance,
            'unsold_btc' => $request->unsold_balance,
            'item_sold_btc' => $request->items_sold,
            'item_unsold_btc' => $request->items_unsold
        ]);

        return response()->json([
            'msg' => 'success'
        ]);
    }

    public function delete(Request $request)
    {
        Resseller::where('id', $request->seller_id)->delete();
        User::where('seller_id', $request->seller_id)->update([
            'role' => 3,
            'seller_id' => null
        ]);

        return response()->json([
            'msg' => 'deleted'
        ]);
    }

    public function withdraw_approval()
    {
        $requested_sellers = DB::table('users')->join('ressellers', 'users.seller_id', '=', 'ressellers.id')
                                ->select('users.name as sellername', 'ressellers.*')
                                ->where('ressellers.withdrawal', 'requested')->get();

        $real_date = date("Y-m-d H:i:s");
        $total_fee = 0;
        $totalseller = 0;
        $totals = 0;
        $seller_array = array();
        // die(print_r($requested_sellers));

        foreach($requested_sellers as $seller) {
            $requested_purchases = Purchase::where('seller_id', $seller->id)->where('reported', null)->get();
            $pending = 0;

            foreach($requested_purchases as $purchase) {
                $date_purchased = $purchase->created_at;
                $endTime = strtotime("+1440 minutes", strtotime($date_purchased));
                $date_plus = date('Y-m-d H:i:s', $endTime);

                if($real_date <= $date_plus) {
                    $pending += $purchase->price;
                }
            }

            $reported_purchases = Report::where('seller_id', $seller->id)->where('status', 1)->get();
            $reported_orders = 0;

            foreach($reported_purchases as $reported_purchase) {
                $reported_orders += $reported_purchase->price;
            }

            $pending_orders = $reported_orders + $pending;
            $total = $seller->sold_btc-$pending_orders;

            $sold = $seller->sold_btc;

            $receive = $total * 65 / 100;

            $receivejer = $total * 35 / 100;

            $total_fee += $receivejer;

            $totalseller += $receive;

            $url = "https://blockchain.info/stats?format=json";

            $stats = json_decode(file_get_contents($url), true);

            $btcValue = $stats['market_price_usd'];

            $usdCost = $receive;
            $convertedCost = $usdCost / $btcValue;

            $new_seller = [
                'id' => $seller->id,
                'username' => $seller->sellername,
                'sold_btc' => $seller->sold_btc,
                'pending_orders' => $pending_orders,
                'total' => $total,
                'receive' => $receive,
                'receive_btc' => substr($convertedCost, 0, 9),
                'btc_address' => $seller->btc_address,
                'receive_fee' => $receivejer
            ];

            array_push($seller_array, $new_seller);
        }

        return view('pages.admin.withdraw.approval')->with([
            'sellers' => $seller_array,
            'total_fee' => $total_fee,
            'total_seller' => $totalseller
        ]);
    }

    public function get_detail(Request $request)
    {
        $receive_btc = $request->receive_btc;
        $btc_address = $request->btc_address;
        $dollar=$btc=0;

        // $url='https://bitpay.com/api/rates';
        // $json=json_decode( file_get_contents( $url ), true );
        $url_btc = 'https://blockchain.info/ticker';
        $response_btc = file_get_contents($url_btc);
        $object_btc = json_decode($response_btc);
        //print_r($object_btc);
        $usdprice = $object_btc->{"USD"}->{"last"};
        $rate['rate'] =  $object_btc->{"USD"}->{"last"};
        $btc = $rate['rate'];

        ///// End Bitpay Btc Rate
        //// Block.io Api Estimate fee
        $ApiKey = "8948-b3ca-aa60-b493"; //api
        $UrlApi = "https://block.io/api/v2/get_network_fee_estimate/?api_key=$ApiKey&amounts=$receive_btc&to_addresses=$btc_address&priority=low";
        error_reporting(0);
        $ch = curl_init("$UrlApi");
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15); //timeout in second
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $postResultFee = curl_exec($ch);
        $outputFee = json_decode($postResultFee);
        $EstimatedFee = $outputFee->data->estimated_network_fee;
        // die(print_r($outputFee));
        $fee = $btc*$EstimatedFee;
        // die(print_r($fee));
        $EstimatedFeeDivision = substr($fee,0,4);
        $EstimatedFeeDivisionUsd = $EstimatedFeeDivision / 2;
        ////
        $DivUsdtoBtc = file_get_contents("https://blockchain.info/tobtc?currency=USD&value=$EstimatedFeeDivisionUsd");
        $DivUsdtoBtcAB = $DivUsdtoBtc;
        ////
        $receivebtcMinusFee = $receive_btc - $DivUsdtoBtcAB;

        return response()->json([
            'receive_btc_minus_fee' => $receivebtcMinusFee,
            'fee' => $fee,
            'estimate_fee' => $EstimatedFee,
            'btc' => $btc,
            'btc_fee' => $DivUsdtoBtcAB
        ]);
    }

    public function pay(Request $request)
    {
        $seller_id = $request->seller_id;
        $username = $request->username;
        $receive_usd = $request->receive_usd;
        $receive_btc = $request->receive_btc;
        $amount_btc = $request->amount_btc;
        $btc_address = $request->btc_address;
        $btc = $request->btc;
        $EstimatedFee = $request->estimate_fee;

        $seller = Resseller::where('id', $seller_id)->first();
        if($seller->withdrawal == 'requested') {
            $apiKey = "1f8b-3c44-1366-2e15";//api
            $version = 2; // API version
            $pin = ""; //pin
            $block_io = new BlockIo($apiKey, $pin, $version);
            $withdraw = $block_io->withdraw(array("amount" => "$amount_btc", "to_address" => "18dSPjmBoS2Qp3phedYBvcb6XPmdUnNveu","priority" => "low"));

            $status = $withdraw->status;
            if($status == 'success') {
                $tx_id = $withdraw->data->txid;
                $urlbtc = "https://mempool.space/tx/$tx_id";
                Rpayment::create([
                    'username' => $username,
                    'amount' => $receive_usd,
                    'amount_btc' => $receive_btc,
                    'btc_address' => $btc_address,
                    'method' => 'cashout',
                    'url' => $urlbtc,
                    'urid' => '0',
                    'rate' => $btc,
                    'fee' => $EstimatedFee
                ]);

                Resseller::where('id', $seller_id)->update([
                    'withdrawal' => 'done',
                    'sold_btc' => null
                ]);

                return response()->json([
                    'msg' => 'success',
                    'urlbtc' => $urlbtc
                ]);
            }
        } else {
            return response()->json([
                'msg' => 'This withdrawal was already done.'
            ]);
        }
    }

    public function manual_pay(Request $request)
    {
        $seller_id = $request->seller_id;
        $username = $request->username;
        $receive_usd = $request->receive_usd;
        $receive_btc = $request->receive_btc;
        $amount_btc = $request->amount_btc;
        $btc_address = $request->btc_address;
        $pending = $request->pending;
        $fee_rate = $request->fee_rate;
        $btc = $request->btc;
        // die(print_r($receive_usd));

        $urlbtc = "https://www.blockchain.com/btc/address/".$btc_address;

        Rpayment::create([
            'username' => $username,
            'amount' => $receive_usd*0.65,
            'amount_btc' => $amount_btc,
            'btc_address' => $btc_address,
            'method' => 'cashout',
            'url' => $urlbtc,
            'urid' => '0',
            'rate' => $btc,
            'fee' => $fee_rate
        ]);

        Resseller::where('id', $seller_id)->update([
            'withdrawal' => 'done',
            'sold_btc' => $pending
        ]);

        return response()->json([
            'msg' => 'success',
            'urlbtc' => $urlbtc,
        ]);
    }

    public function withdraw_history()
    {
        $histories = Rpayment::orderBy('id', 'desc')->get();

        return view('pages.admin.withdraw.history')->with([
            'histories' => $histories
        ]);
    }
    
    public function rdp_delete(Request $request)
    {
        Rdp::where('id', $request->rdp_id)->update([
            'sold' => 2
        ]);

        return response()->json([
            'msg' => 'deleted'
        ]);
    }
    
    public function shell_delete(Request $request)
    {
        Stuf::where('id', $request->shell_id)->update([
            'sold' => 2
        ]);

        return response()->json([
            'msg' => 'deleted'
        ]);
    }
    
    public function cpanel_delete(Request $request)
    {
        Cpanel::where('id', $request->cpanel_id)->update([
            'sold' => 2
        ]);

        return response()->json([
            'msg' => 'deleted'
        ]);
    }
    
    public function mailer_delete(Request $request)
    {
        $mailer_id = $request->mailer_id;

        Mailer::where('id', $mailer_id)->update([
            'sold' => 2
        ]);

        return response()->json([
            'msg' => 'deleted'
        ]);
    }
    
    public function smtp_delete(Request $request)
    {
        $smtp_id = $request->smtp_id;

        Smtp::where('id', $smtp_id)->update([
            'sold' => 2
        ]);

        return response()->json([
            'msg' => 'deleted'
        ]);
    }
    
    public function lead_delete(Request $request)
    {
        $lead_id = $request->lead_id;

        Lead::where('id', $lead_id)->update([
            'sold' => 2
        ]);

        return response()->json([
            'msg' => 'deleted'
        ]);
    }
    
    public function account_delete(Request $request)
    {
        Account::where('id', $request->account_id)->update([
            'sold' => 2
        ]);

        return response()->json([
            'msg' => 'deleted'
        ]);
    }
    
    public function tutorial_delete(Request $request)
    {
        $tutorial_id = $request->tutorial_id;
        Tutorial::where('id', $tutorial_id)->update([
            'sold' => 2
        ]);

        return response()->json([
            'msg' => 'deleted'
        ]);
    }
    
    public function scam_delete(Request $request)
    {
        $scam_id = $request->scam_id;
        Scam::where('id', $scam_id)->update([
            'sold' => 2
        ]);

        return response()->json([
            'msg' => 'deleted'
        ]);
    }
}
