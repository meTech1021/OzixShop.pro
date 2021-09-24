<?php
namespace App\Http\Controllers;

use App\Models\Cpanel;
use App\Models\Rdp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Resseller;
use App\Models\Stuf;

class HostController extends Controller
{

    public function buy(Request $request)
    {
        $table = $request->table;
        $id = $request->data_id;

        $tool = DB::table($table)->where('id', $id)->first();
        if(Auth::user()->balance < $tool->price) {
            $msg = 'no balance';
        } else {
            if($tool->sold == 0) {
                DB::table($table)->where('id', $id)->update([
                    'sold' => 1,
                    'sto' => Auth::user()->id,
                    'seller_id' => $tool->seller_id
                ]);

                User::where('id', Auth::user()->id)->update([
                    'balance' => Auth::user()->balance - $tool->price,
                    'ipurchassed' => Auth::user()->ipurchassed+1
                ]);

                $seller = Resseller::where('id', $tool->seller_id)->first();
                Resseller::where('id', $seller->id)->update([
                    'sold_btc' => $seller->sold_btc+$tool->price
                ]);

                Purchase::create([
                    's_id' => Auth::user()->id,
                    'buyer' => Auth::user()->name,
                    'item_id' => $tool->id,
                    'type' => $tool->acctype,
                    'country' => $tool->country,
                    'country_full' => $tool->country_full,
                    'infos' => $tool->infos,
                    'url' => $tool->url,
                    'price' =>$tool->price,
                    'seller_id' => $tool->seller_id,
                    'reported' => null,
                    'report_id' => null
                ]);
                $msg = 'success';
            } else {
                $msg = 'Already sold or deleted.';
            }
        }

        return response()->json([
            'msg' => $msg
        ]);

    }

    public function rdps()
    {
        $rdps = Rdp::where('sold', 0)->orderBy(DB::raw('RAND()'))->get();
        $countries = Rdp::where('sold', 0)->select('country', 'country_full')->distinct()->get();
        $windows = Rdp::where('sold', 0)->select('windows')->distinct()->get();
        $sellers = Rdp::where('sold', 0)->select('seller_id')->distinct()->get();

        return view('pages.buyer.host.rdp')->with([
            'rdps' => $rdps,
            'countries' => $countries,
            'windows' => $windows,
            'sellers' => $sellers,
        ]);
    }

    public function rdp_check(Request $request)
    {
        $rdp = Rdp::where('id', $request->rdp_id)->first();
        $url = $rdp->url;
        $infos = explode('|', $url);

        $ip = $infos[0];
        $urltoapi = "https://apichkup.herokuapp.com/chkrdp.php?host=".$ip;

        $urltoapi2 = file_get_contents($urltoapi);

	    if (preg_match('#working#', $urltoapi2)) {
            $msg = 'working';
        } else {
            $msg = 'not working';
            Rdp::where('id', $request->rdp_id)->update([
                'sold' => 2
            ]);
        }

        return response()->json([
            'msg' => $msg
        ]);
    }

    public function rdp_filter(Request $request)
    {
        $infos = $request->infos;
        $ram = $request->ram;
        $windows = $request->windows;
        $country = $request->country;
        $source = $request->source;
        $access = $request->access;
        $seller = $request->seller;
        $min_price = $request->min_price;
        $max_price = $request->max_price;

        if($windows == 'all') {
            $windows = '';
        }
        if($country == 'all') {
            $country = '';
        }
        if($source == 'all') {
            $source = '';
        }
        if($access == 'all') {
            $access = '';
        }

        if($min_price == '') {
            $min_price = 0;
        }
        if($max_price == '') {
            $max_price = 0;
        }

        if($seller == 'all') {
            if($max_price != 0) {
                $rdps = Rdp::where('infos', 'like', $infos.'%')
                            ->where('ram', 'like', $ram.'%')
                            ->where('windows', 'like', $windows.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('source', 'like', $source.'%')
                            ->where('access', 'like', $access.'%')
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->get();
            } else {
                $rdps = Rdp::where('infos', 'like', $infos.'%')
                            ->where('ram', 'like', $ram.'%')
                            ->where('windows', 'like', $windows.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('source', 'like', $source.'%')
                            ->where('access', 'like', $access.'%')
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->get();
            }


        } else {
            if($max_price != 0) {
                $rdps = Rdp::where('infos', 'like', $infos.'%')
                            ->where('ram', 'like', $ram.'%')
                            ->where('windows', 'like', $windows.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('source', 'like', $source.'%')
                            ->where('access', 'like', $access.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->get();
            } else {
                $rdps = Rdp::where('infos', 'like', $infos.'%')
                            ->where('ram', 'like', $ram.'%')
                            ->where('windows', 'like', $windows.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('source', 'like', $source.'%')
                            ->where('access', 'like', $access.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->get();
            }

        }

        return response()->json([
            'rdps' => $rdps
        ]);
    }

    public function cpanels()
    {
        $cpanels = Cpanel::where('sold', 0)->orderBy(DB::raw('RAND()'))->get();
        $countries = Cpanel::where('sold', 0)->select('country', 'country_full')->distinct()->get();
        $sellers = Cpanel::where('sold', 0)->select('seller_id')->distinct()->get();

        return view('pages.buyer.host.cpanel')->with([
            'cpanels' => $cpanels,
            'countries' => $countries,
            'sellers' => $sellers,
        ]);
    }

    public function cpanel_filter(Request $request)
    {
        $infos = $request->infos;
        $ssl = $request->ssl;
        $country = $request->country;
        $source = $request->source;
        $seller = $request->seller;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $tld = $request->tld;

        if($ssl == 'all') {
            $ssl = '';
        }
        if($country == 'all') {
            $country = '';
        }
        if($source == 'all') {
            $source = '';
        }

        if($min_price == '') {
            $min_price = 0;
        }
        if($max_price == '') {
            $max_price = 0;
        }

        if($seller == 'all') {
            if($max_price != 0) {
                $cpanels = Cpanel::where('infos', 'like', $infos.'%')
                            ->where('url','like', '%'.$tld.'%')
                            ->where('ssl_status', 'like', $ssl.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('source', 'like', $source.'%')
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->get();
            } else {
                $cpanels = Cpanel::where('infos', 'like', $infos.'%')
                            ->where('url','like', '%'.$tld.'%')
                            ->where('ssl_status', 'like', $ssl.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('source', 'like', $source.'%')
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->get();
            }


        } else {
            if($max_price != 0) {
                $cpanels = Cpanel::where('infos', 'like', $infos.'%')
                            ->where('url','like', '%'.$tld.'%')
                            ->where('ssl_status', 'like', $ssl.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('source', 'like', $source.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->get();
            } else {
                $cpanels = Cpanel::where('infos', 'like', $infos.'%')
                            ->where('url','like', '%'.$tld.'%')
                            ->where('ssl_status', 'like', $ssl.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('source', 'like', $source.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->get();
            }

        }

        return response()->json([
            'cpanels' => $cpanels
        ]);
    }

    public function cpanel_check(Request $request)
    {
        $cpanel = Cpanel::where('id', $request->cpanel_id)->first();
        $url = $cpanel->url;
        $infos = explode('|', $url);
        $host = $infos[0];
        $username = $infos[1];
        $password = $infos[2];

        $host = parse_url($host, PHP_URL_HOST);
        // die(print_r($host));
        $urltoapi = "https://apichkup.herokuapp.com/chkcp.php?cp12=".$host."&login=$username&pass=".rawurlencode($password)."";
        // die(print_r($urltoapi));
        $urltoapi2 = file_get_contents($urltoapi);
        // die(print_r($urltoapi2));
	    if (preg_match('#CP Work#', $urltoapi2)) {
            $msg = 'working';
        } else {
            $msg = 'not working';
            Cpanel::where('id', $request->cpanel_id)->update([
                'sold' => 2
            ]);
        }

        return response()->json([
            'msg' => $msg
        ]);
    }

    public function shells()
    {
        $shells = Stuf::where('sold', 0)->orderBy(DB::raw('RAND()'))->get();
        $countries = Stuf::where('sold', 0)->select('country', 'country_full')->distinct()->get();
        $sellers = Stuf::where('sold', 0)->select('seller_id')->distinct()->get();
        // die(print_r($shells));
        return view('pages.buyer.host.shell')->with([
            'shells' => $shells,
            'countries' => $countries,
            'sellers' => $sellers
        ]);
    }

    public function shell_check(Request $request)
    {
        $shell_id = $request->shell_id;
        $shell = Stuf::where('id',$shell_id)->first();

        $ch =  curl_init();
        curl_setopt($ch, CURLOPT_URL, $shell->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:23.0) Gecko/20100101 Firefox/23.0');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $output = curl_exec($ch);
        curl_close($ch);
        if(preg_match('#Uname:|Safe mode: OFF|Client IP:|Server IP:|Your IP:|Last Modified#si',$output)){
            $msg = 'working';
        } else {
            $msg = 'not working';
            Stuf::where('id', $shell_id)->update([
                'sold' => 2
            ]);
        }

        return response()->json([
            'msg' => $msg
        ]);
    }

    public function shell_filter(Request $request)
    {
        $infos = $request->infos;
        $ssl = $request->ssl;
        $country = $request->country;
        $source = $request->source;
        $seller = $request->seller;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $tld = $request->tld;

        if($ssl == 'all') {
            $ssl = '';
        }
        if($country == 'all') {
            $country = '';
        }
        if($source == 'all') {
            $source = '';
        }

        if($min_price == '') {
            $min_price = 0;
        }
        if($max_price == '') {
            $max_price = 0;
        }

        if($seller == 'all') {
            if($max_price != 0) {
                $shells = Stuf::where('infos', 'like', $infos.'%')
                            ->where('url','like', '%'.$tld.'%')
                            ->where('ssl_status', 'like', $ssl.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('source', 'like', $source.'%')
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->get();
            } else {
                $shells = Stuf::where('infos', 'like', $infos.'%')
                            ->where('url','like', '%'.$tld.'%')
                            ->where('ssl_status', 'like', $ssl.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('source', 'like', $source.'%')
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->get();
            }


        } else {
            if($max_price != 0) {
                $shells = Stuf::where('infos', 'like', $infos.'%')
                            ->where('url','like', '%'.$tld.'%')
                            ->where('ssl_status', 'like', $ssl.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('source', 'like', $source.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->get();
            } else {
                $shells = Stuf::where('infos', 'like', $infos.'%')
                            ->where('url','like', '%'.$tld.'%')
                            ->where('ssl_status', 'like', $ssl.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('source', 'like', $source.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->get();
            }

        }

        return response()->json([
            'shells' => $shells
        ]);
    }
}
