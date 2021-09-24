<?php
namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function marketing()
    {
        $datas = Account::where('sold', 0)->where('acctype', 'Marketing')->orderBy(DB::raw('RAND()'))->get();
        $countries = Account::where('sold', 0)->select('country', 'country_full')->distinct()->get();
        $sellers = Account::where('sold', 0)->select('seller_id')->distinct()->get();

        return view('pages.buyer.account.marketing')->with([
            'datas' => $datas,
            'countries' => $countries,
            'sellers' => $sellers
        ]);
    }

    public function hosting()
    {
        $datas = Account::where('sold', 0)->where('acctype', 'Hosting/Domain')->orderBy(DB::raw('RAND()'))->get();
        $countries = Account::where('sold', 0)->select('country', 'country_full')->distinct()->get();
        $sellers = Account::where('sold', 0)->select('seller_id')->distinct()->get();

        return view('pages.buyer.account.hosting')->with([
            'datas' => $datas,
            'countries' => $countries,
            'sellers' => $sellers
        ]);
    }

    public function games()
    {
        $datas = Account::where('sold', 0)->where('acctype', 'Games')->orderBy(DB::raw('RAND()'))->get();
        $countries = Account::where('sold', 0)->select('country', 'country_full')->distinct()->get();
        $sellers = Account::where('sold', 0)->select('seller_id')->distinct()->get();

        return view('pages.buyer.account.games')->with([
            'datas' => $datas,
            'countries' => $countries,
            'sellers' => $sellers
        ]);
    }

    public function vpn()
    {
        $datas = Account::where('sold', 0)->where('acctype', 'VPN/Socks Proxy')->orderBy(DB::raw('RAND()'))->get();
        $countries = Account::where('sold', 0)->select('country', 'country_full')->distinct()->get();
        $sellers = Account::where('sold', 0)->select('seller_id')->distinct()->get();

        return view('pages.buyer.account.vpn')->with([
            'datas' => $datas,
            'countries' => $countries,
            'sellers' => $sellers
        ]);
    }

    public function shopping()
    {
        $datas = Account::where('sold', 0)->where('acctype', 'Shopping {Amazon, eBay .... etc }')->orderBy(DB::raw('RAND()'))->get();
        $countries = Account::where('sold', 0)->select('country', 'country_full')->distinct()->get();
        $sellers = Account::where('sold', 0)->select('seller_id')->distinct()->get();

        return view('pages.buyer.account.shopping')->with([
            'datas' => $datas,
            'countries' => $countries,
            'sellers' => $sellers
        ]);
    }

    public function stream()
    {
        $datas = Account::where('sold', 0)->where('acctype', 'Stream { Music, Netflix, iptv, HBO, bein sport, WWE ...etc }')->orderBy(DB::raw('RAND()'))->get();
        $countries = Account::where('sold', 0)->select('country', 'country_full')->distinct()->get();
        $sellers = Account::where('sold', 0)->select('seller_id')->distinct()->get();

        return view('pages.buyer.account.stream')->with([
            'datas' => $datas,
            'countries' => $countries,
            'sellers' => $sellers
        ]);
    }

    public function dating()
    {
        $datas = Account::where('sold', 0)->where('acctype', 'Dating')->orderBy(DB::raw('RAND()'))->get();
        $countries = Account::where('sold', 0)->select('country', 'country_full')->distinct()->get();
        $sellers = Account::where('sold', 0)->select('seller_id')->distinct()->get();

        return view('pages.buyer.account.dating')->with([
            'datas' => $datas,
            'countries' => $countries,
            'sellers' => $sellers
        ]);
    }

    public function learning()
    {
        $datas = Account::where('sold', 0)->where('acctype', 'Learning { udemy, lynda, .... etc. }')->orderBy(DB::raw('RAND()'))->get();
        $countries = Account::where('sold', 0)->select('country', 'country_full')->distinct()->get();
        $sellers = Account::where('sold', 0)->select('seller_id')->distinct()->get();

        return view('pages.buyer.account.learning')->with([
            'datas' => $datas,
            'countries' => $countries,
            'sellers' => $sellers
        ]);
    }

    public function voip()
    {
        $datas = Account::where('sold', 0)->where('acctype', 'Voip/Sip')->orderBy(DB::raw('RAND()'))->get();
        $countries = Account::where('sold', 0)->select('country', 'country_full')->distinct()->get();
        $sellers = Account::where('sold', 0)->select('seller_id')->distinct()->get();

        return view('pages.buyer.account.voip')->with([
            'datas' => $datas,
            'countries' => $countries,
            'sellers' => $sellers
        ]);
    }

    public function marketing_filter(Request $request, $type)
    {

        if($type == 'marketing') {
            $search_key = 'Marketing';
        } elseif($type == 'hosting') {
            $search_key = 'Hosting/Domain';
        } elseif($type == 'games') {
            $search_key = 'Games';
        } elseif($type == 'vpn') {
            $search_key = 'VPN/Socks Proxy';
        } elseif($type == 'shopping') {
            $search_key = 'Shopping {Amazon, eBay .... etc }';
        } elseif($type == 'stream') {
            $search_key = 'Stream { Music, Netflix, iptv, HBO, bein sport, WWE ...etc }';
        } elseif($type == 'dating') {
            $search_key = 'Dating';
        } elseif($type == 'learning') {
            $search_key = 'Learning { udemy, lynda, .... etc. }';
        } elseif($type == 'voip') {
            $search_key = 'Voip/Sip';
        }

        $infos = $request->infos;
        $domain = $request->domain;
        $country = $request->country;
        $seller = $request->seller;
        $min_price = $request->min_price;
        $max_price = $request->max_price;

        if($country == 'all') {
            $country = '';
        }

        if($min_price == '') {
            $min_price = 0;
        }
        if($max_price == '') {
            $max_price = 0;
        }

        if($seller == 'all') {
            if($max_price != 0) {
                $accounts = Account::where('infos', 'like', '%'.$infos.'%')
                            ->where('sitename', 'like', '%'.$domain.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->where('acctype', $search_key)
                            ->get();
            } else {
                $accounts = Account::where('infos', 'like', '%'.$infos.'%')
                            ->where('sitename', 'like', '%'.$domain.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->where('acctype', $search_key)
                            ->get();
            }


        } else {
            if($max_price != 0) {
                $accounts = Account::where('infos', 'like', '%'.$infos.'%')
                            ->where('sitename', 'like', '%'.$domain.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->where('acctype', $search_key)
                            ->get();
            } else {
                $accounts = Account::where('infos', 'like', '%'.$infos.'%')
                            ->where('sitename', 'like', '%'.$domain.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->where('acctype', $search_key)
                            ->get();
            }

        }

        return response()->json([
            'accounts' => $accounts
        ]);
    }
}
