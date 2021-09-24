<?php
namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{
    public function checked_list()
    {
        $checked_list = Lead::where('sold', 0)->where('acctype', '100% Email Checked List')->orderBy(DB::raw('RAND()'))->get();
        $countries = Lead::where('sold', 0)->select('country', 'country_full')->distinct()->get();
        $sellers = Lead::where('sold', 0)->select('seller_id')->distinct()->get();
        return view('pages.buyer.lead.checked_list')->with([
            'checked_list' => $checked_list,
            'countries' => $countries,
            'sellers' => $sellers
        ]);
    }

    public function checked_list_filter(Request $request)
    {
        $infos = $request->infos;
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
                $leads = Lead::where('infos', 'like', $infos.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->where('acctype', '100% Email Checked List')
                            ->get();
            } else {
                $leads = Lead::where('infos', 'like', $infos.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->where('acctype', '100% Email Checked List')
                            ->get();
            }


        } else {
            if($max_price != 0) {
                $leads = Lead::where('infos', 'like', $infos.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->where('acctype', '100% Email Checked List')
                            ->get();
            } else {
                $leads = Lead::where('infos', 'like', $infos.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->where('acctype', '100% Email Checked List')
                            ->get();
            }

        }

        return response()->json([
            'leads' => $leads
        ]);
    }

    public function email_list()
    {
        $email_list = Lead::where('sold', 0)->where('acctype', 'Email List')->orderBy(DB::raw('RAND()'))->get();
        $countries = Lead::where('sold', 0)->select('country', 'country_full')->distinct()->get();
        $sellers = Lead::where('sold', 0)->select('seller_id')->distinct()->get();
        return view('pages.buyer.lead.email_list')->with([
            'email_list' => $email_list,
            'countries' => $countries,
            'sellers' => $sellers
        ]);
    }

    public function email_list_filter(Request $request)
    {
        $infos = $request->infos;
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
                $leads = Lead::where('infos', 'like', $infos.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->where('acctype', 'Email List')
                            ->get();
            } else {
                $leads = Lead::where('infos', 'like', $infos.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->where('acctype', 'Email List')
                            ->get();
            }


        } else {
            if($max_price != 0) {
                $leads = Lead::where('infos', 'like', $infos.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->where('acctype', 'Email List')
                            ->get();
            } else {
                $leads = Lead::where('infos', 'like', $infos.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->where('acctype', 'Email List')
                            ->get();
            }

        }

        return response()->json([
            'leads' => $leads
        ]);
    }

    public function combo_list()
    {
        $combo_list = Lead::where('sold', 0)->where('acctype', 'Combo List')->orderBy(DB::raw('RAND()'))->get();
        $countries = Lead::where('sold', 0)->select('country', 'country_full')->distinct()->get();
        $sellers = Lead::where('sold', 0)->select('seller_id')->distinct()->get();
        return view('pages.buyer.lead.combo_list')->with([
            'combo_list' => $combo_list,
            'countries' => $countries,
            'sellers' => $sellers
        ]);
    }

    public function combo_list_filter(Request $request)
    {
        $infos = $request->infos;
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
                $leads = Lead::where('infos', 'like', $infos.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->where('acctype', 'Combo List')
                            ->get();
            } else {
                $leads = Lead::where('infos', 'like', $infos.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->where('acctype', 'Combo List')
                            ->get();
            }


        } else {
            if($max_price != 0) {
                $leads = Lead::where('infos', 'like', $infos.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->where('acctype', 'Combo List')
                            ->get();
            } else {
                $leads = Lead::where('infos', 'like', $infos.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->where('acctype', 'Combo List')
                            ->get();
            }

        }

        return response()->json([
            'leads' => $leads
        ]);
    }
}
