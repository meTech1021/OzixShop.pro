<?php
namespace App\Http\Controllers;

use App\Models\Scam;
use App\Models\Tutorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OtherController extends Controller
{
    public function scampage()
    {
        $datas = Scam::where('sold', 0)->orderBy(DB::raw('RAND()'))->get();
        $sellers = Scam::where('sold', 0)->select('seller_id')->distinct()->get();
        $countries = Scam::where('sold', 0)->select('country', 'country_full')->distinct()->get();

        return view('pages.buyer.others.scam')->with([
            'datas' => $datas,
            'sellers' => $sellers,
            'countries' => $countries
        ]);
    }

    public function scam_filter(Request $request)
    {
        $scriptname = $request->scriptname;
        $infos = $request->infos;
        $seller = $request->seller;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $country = $request->country;

        if($min_price == '') {
            $min_price = 0;
        }
        if($max_price == '') {
            $max_price = 0;
        }

        if($seller == 'all') {
            if($max_price != 0) {
                $scams = Scam::where('infos', 'like', '%'.$infos.'%')
                            ->where('scam_name', 'like', '%'.$scriptname.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->get();
            } else {
                $scams = Scam::where('infos', 'like', '%'.$infos.'%')
                            ->where('scam_name', 'like', '%'.$scriptname.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->get();
            }


        } else {
            if($max_price != 0) {
                $scams = Scam::where('infos', 'like', '%'.$infos.'%')
                            ->where('scam_name', 'like', '%'.$scriptname.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->get();
            } else {
                $scams = Scam::where('infos', 'like', '%'.$infos.'%')
                            ->where('scam_name', 'like', '%'.$scriptname.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->get();
            }

        }

        return response()->json([
            'scams' => $scams
        ]);
    }

    public function tutorials()
    {
        $datas = Tutorial::where('sold', 0)->orderBy(DB::raw('RAND()'))->get();
        $sellers = Tutorial::where('sold', 0)->select('seller_id')->distinct()->get();
        $countries = Tutorial::where('sold', 0)->select('country', 'country_full')->distinct()->get();

        return view('pages.buyer.others.tutorials')->with([
            'datas' => $datas,
            'sellers' => $sellers,
            'countries' => $countries
        ]);
    }

    public function tutorial_filter(Request $request)
    {
        $scriptname = $request->scriptname;
        $infos = $request->infos;
        $seller = $request->seller;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $country = $request->country;

        // die(print_r($scriptname));
        if($min_price == '') {
            $min_price = 0;
        }
        if($max_price == '') {
            $max_price = 0;
        }

        if($seller == 'all') {
            if($max_price != 0) {
                $tutorials = Tutorial::where('infos', 'like', '%'.$infos.'%')
                            ->where('tutorial_name', 'like', '%'.$scriptname.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->get();
            } else {
                $tutorials = Tutorial::where('infos', 'like', '%'.$infos.'%')
                            ->where('tutorial_name', 'like', '%'.$scriptname.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->get();
            }


        } else {
            if($max_price != 0) {
                $tutorials = Tutorial::where('infos', 'like', '%'.$infos.'%')
                            ->where('tutorial_name', 'like', '%'.$scriptname.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->get();
            } else {
                $tutorials = Tutorial::where('infos', 'like', '%'.$infos.'%')
                            ->where('tutorial_name', 'like', '%'.$scriptname.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->get();
            }

        }

        return response()->json([
            'tutorials' => $tutorials
        ]);
    }
}
