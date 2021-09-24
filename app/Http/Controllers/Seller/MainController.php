<?php
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Cpanel;
use App\Models\Lead;
use App\Models\Mailer;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\News;
use App\Models\Order;
use App\Models\PaymentHistory;
use App\Models\Purchase;
use App\Models\Rdp;
use App\Models\Report;
use App\Models\Resseller;
use App\Models\Rpayment;
use App\Models\Scam;
use App\Models\Smtp;
use App\Models\Stuf;
use App\Models\Tutorial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function dashboard()
    {
        $top_sellers = DB::table('users')->join('ressellers', 'users.seller_id', '=', 'ressellers.id')
                            ->select('users.name as name', 'ressellers.*')->orderBy('ressellers.sold_btc', 'desc')->limit(10)->get();
        $news = News::where('type', 1)->orderBy('created_at', 'desc')->limit(5)->get();
        $seller = Resseller::where('id', Auth::user()->seller_id)->first();

        return view('pages.seller.dashboard')->with([
            'top_sellers' => $top_sellers,
            'news' => $news,
            'seller' => $seller
        ]);
    }

    public function sales()
    {

        $total_sales = Purchase::select(DB::raw('sum(price) as total_sales'))->where('seller_id', Auth::user()->seller_id)->first();
        $sales = Purchase::where('seller_id', Auth::user()->seller_id)->orderBy('id', 'desc')->get();

        if($total_sales->total_sales == null)
        {
            $total_sales = 0;
        } else {
            $total_sales = $total_sales->total_sales;
        }

        $sales_arr = array();

        foreach($sales as $sale) {
            $report = Report::where('order_id', $sale->id)->first();
            $new_sale = [
                'id' => $sale->id,
                's_id' => $sale->s_id,
                'buyer' => $sale->buyer,
                'type' => $sale->type,
                'country' => $sale->country,
                'infos' => $sale->infos,
                'url' => $sale->url,
                'login' => $sale->login,
                'pass' => $sale->pass,
                'price' => $sale->price,
                'seller_id' => $sale->seller_id,
                'reported' => $sale->reported,
                'report_id' => $sale->report_id,
                'created_at' => $sale->created_at,
                'updated_at' => $sale->updated_at,
                'report_state' => $sale->state
            ];

            array_push($sales_arr, $new_sale);
        }

        return view('pages.seller.orders')->with([
            'sales' => $sales_arr,
            'total_sales' => $total_sales,
            'orders' => $sales_arr
        ]);
    }

    public function withdraw()
    {
        $payment_histories = Rpayment::where('username', Auth::user()->name)->orderBy('created_at', 'desc')->get();

        $resseller = Resseller::where('user_id', Auth::user()->id)->first();

        $pending = 0;
        $t=0;
        $real_data = date("Y-m-d H:i:s");

        $purchases = Purchase::where('seller_id', Auth::user()->seller_id)->where('reported', '')->get();
        foreach($purchases as $purchase)
        {
            $date_purchased = $purchase->created_at;
            $endTime        = strtotime("+1440 minutes", strtotime($date_purchased));
            $data_plus      = date('Y-m-d H:i:s', $endTime);
            if ($real_data > $data_plus) {

            } else {
                $pending += $purchase->price;
                $t++;
            }
        }

        $reports = Report::where('seller_id', Auth::user()->seller_id)->where('status', 1)->get();
        $reported_orders = 0;
        foreach($reports as $report)
        {
            $reported_orders += $report->price;
            $t++;
        }
        $pending_orders = $reported_orders + $pending;
        if($resseller == null)
        {
            $sold_btc = 0;
        } else {
            $sold_btc = $resseller->sold_btc;
        }
        $total = $sold_btc - $pending_orders;

        return view('pages.seller.withdraws')->with([
            'payment_histories' => $payment_histories,
            'resseller' => $resseller,
            't' => $t,
            'pending_orders' => $pending_orders,
            'total' => $total
        ]);
    }

    public function change_btc_address(Request $request)
    {
        Resseller::where('user_id', Auth::user()->id)->update([
            'btc_address' => $request->btc_address
        ]);

        return response()->json([
            'msg' => 'success'
        ]);
    }

    public function request(Request $request)
    {
        Resseller::where('id', Auth::user()->seller_id)->update([
            'withdrawal' => 'requested'
        ]);

        return response()->json([
            'msg' => 'requested'
        ]);
    }

    public function myreports()
    {
        $pending_reports = Report::where('seller_id', Auth::user()->seller_id)->where('status', 1)->get();
        $reports = Report::where('seller_id', Auth::user()->seller_id)->get();

        return view('pages.seller.myreports')->with([
            'pending_reports' => $pending_reports,
            'reports' => $reports
        ]);
    }

    public function report_view($id)
    {
        $report = Report::where('id', $id)->first();

        if($report->acctype == 'cPanel') {
            $cpanel = Cpanel::where('id', $report->s_id)->first();
            return view('pages.seller.report_view')->with([
                'report' => $report,
                'cpanel' => $cpanel
            ]);
        } elseif($report->acctype == 'Shell') {
            $shell = Stuf::where('id', $report->s_id)->first();
            return view('pages.seller.report_view')->with([
                'report' => $report,
                'shell' => $shell
            ]);
        } elseif($report->acctype == 'RDP') {
            $rdp = Rdp::where('id', $report->s_id)->first();
            return view('pages.seller.report_view')->with([
                'report' => $report,
                'rdp' => $rdp
            ]);
        } elseif($report->acctype == 'Mailer') {
            $mailer = Mailer::where('id', $report->s_id)->first();
            return view('pages.seller.report_view')->with([
                'report' => $report,
                'mailer' => $mailer
            ]);
        } elseif($report->acctype == 'SMTP') {
            $smtp = Smtp::where('id', $report->s_id)->first();
            return view('pages.seller.report_view')->with([
                'report' => $report,
                'smtp' => $smtp
            ]);
        } elseif($report->acctype == 'Email List') {
            $email_list = Lead::where('id', $report->s_id)->first();
            return view('pages.seller.report_view')->with([
                'report' => $report,
                'email_list' => $email_list
            ]);
        } elseif($report->acctype == 'Combo List') {
            $combo_list = Lead::where('id', $report->s_id)->first();
            return view('pages.seller.report_view')->with([
                'report' => $report,
                'combo_list' => $combo_list
            ]);
        } elseif($report->acctype == '100% Email Checked List') {
            $checked_list = Lead::where('id', $report->s_id)->first();
            return view('pages.seller.report_view')->with([
                'report' => $report,
                'checked_list' => $checked_list
            ]);
        } elseif($report->acctype == 'Hosting/Domain') {
            $hosting = Account::where('id', $report->s_id)->first();
            return view('pages.seller.report_view')->with([
                'report' => $report,
                'hosting' => $hosting
            ]);
        } elseif($report->acctype == 'Marketing') {
            $marketing = Account::where('id', $report->s_id)->first();
            return view('pages.seller.report_view')->with([
                'report' => $report,
                'marketing' => $marketing
            ]);
        } elseif($report->acctype == 'Games') {
            $game = Account::where('id', $report->s_id)->first();
            return view('pages.seller.report_view')->with([
                'report' => $report,
                'game' => $game
            ]);
        } elseif($report->acctype == 'VPN/Socks Proxy') {
            $vpn = Account::where('id', $report->s_id)->first();
            return view('pages.seller.report_view')->with([
                'report' => $report,
                'vpn' => $vpn
            ]);
        } elseif($report->acctype == 'Shopping {Amazon, eBay .... etc }') {
            $shopping = Account::where('id', $report->s_id)->first();
            return view('pages.seller.report_view')->with([
                'report' => $report,
                'shopping' => $shopping
            ]);
        } elseif($report->acctype == 'Stream { Music, Netflix, iptv, HBO, bein sport, WWE ...etc }') {
            $stream = Account::where('id', $report->s_id)->first();
            return view('pages.seller.report_view')->with([
                'report' => $report,
                'stream' => $stream
            ]);
        } elseif($report->acctype == 'Dating') {
            $dating = Account::where('id', $report->s_id)->first();
            return view('pages.seller.report_view')->with([
                'report' => $report,
                'dating' => $dating
            ]);
        } elseif($report->acctype == 'Voip/Sip') {
            $voip = Account::where('id', $report->s_id)->first();
            return view('pages.seller.report_view')->with([
                'report' => $report,
                'voip' => $voip
            ]);
        } elseif($report->acctype == 'Learning { udemy, lynda, .... etc. }') {
            $learning = Account::where('id', $report->s_id)->first();
            return view('pages.seller.report_view')->with([
                'report' => $report,
                'learning' => $learning
            ]);
        } elseif($report->acctype == 'Exploit/Script/ScamPage') {
            $scam = Scam::where('id', $report->s_id)->first();
            return view('pages.seller.report_view')->with([
                'report' => $report,
                'scam' => $scam
            ]);
        } elseif($report->acctype == 'Tutorial / Method') {
            $tutorial = Tutorial::where('id', $report->s_id)->first();
            return view('pages.seller.report_view')->with([
                'report' => $report,
                'tutorial' => $tutorial
            ]);
        }
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

    public function report_reply(Request $request)
    {
        $msg = $request->message;
        $report_id = $request->report_id;
        $date = date("d/m/Y h:i:s");
        $msg = '<div class="panel panel-default" style="border : 1px solid #00bbb1!important;"><div class="panel-body"><div class="report"><b>'.htmlspecialchars($msg).'</b></div></div><div class="panel-footer"><div class="label label-success">Seller'.Auth::user()->seller_id.'</div> - '.$date.'</div></div>';
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
}
