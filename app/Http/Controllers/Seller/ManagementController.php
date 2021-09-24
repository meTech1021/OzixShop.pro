<?php
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Cpanel;
use App\Models\Lead;
use App\Models\Mailer;
use App\Models\Rdp;
use App\Models\Scam;
use App\Models\Smtp;
use App\Models\Stuf;
use App\Models\Tutorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagementController extends Controller
{
    public function rdp_show()
    {
        $rdps = Rdp::where('seller_id', Auth::user()->seller_id)->get();
        $unsold_rdps_cnt = Rdp::where('sold', 0)->where('seller_id', Auth::user()->seller_id)->count();
        $sold_rdps_cnt = Rdp::where('sold', 1)->where('seller_id', Auth::user()->seller_id)->count();
        $deleted_rdps_cnt = Rdp::where('sold', 2)->where('seller_id', Auth::user()->seller_id)->count();
        return view('pages.seller.management.rdp')->with([
            'rdps' => $rdps,
            'unsold_rdps_cnt' => $unsold_rdps_cnt,
            'sold_rdps_cnt' => $sold_rdps_cnt,
            'deleted_rdps_cnt' => $deleted_rdps_cnt
        ]);
    }

    public function ambilKata($param, $kata1, $kata2){

        if(strpos($param, $kata1) === FALSE) return FALSE;

        if(strpos($param, $kata2) === FALSE) return FALSE;

        $start = strpos($param, $kata1) + strlen($kata1);

        $end = strpos($param, $kata2, $start);

        $return = substr($param, $start, $end - $start);

        return $return;

    }

    public function rdp_save(Request $request)
    {
        $url = $request->host.'|'.$request->username.'|'.$request->password;

        $isempty_url = Rdp::where('url', $url)->count();

        if($isempty_url > 0) {
            return response()->json([
                'msg' => 'host exist'
            ]);
        } else {
            $urltoapi = "https://apichkup.herokuapp.com/chkrdp.php?host=".$request->host;
            $urltoapi2 = file_get_contents($urltoapi);
            if(preg_match('#bad#', $urltoapi2)){
                return response()->json([
                    'msg' => 'not working'
                ]);
            } else {
                $host = $request->host;
                $iptohosting = "http://api.ipgeolocation.io/ipgeo?apiKey=7b0bbc42ff6a445f86bd907ecccd5618&ip=$host&fields=isp";

                $creatorhosting = file_get_contents($iptohosting);

                $hostingg = $this->ambilkata($creatorhosting, '"isp":"','"}');

                $iptocountry = "http://api.ipgeolocation.io/ipgeo?apiKey=7b0bbc42ff6a445f86bd907ecccd5618&ip=$host&fields=country_name";

                $creatorcountry = file_get_contents($iptocountry);

                $countryy = $this->ambilkata($creatorcountry, '"country_name":"','"}');

                $iptocity = "http://api.ipgeolocation.io/ipgeo?apiKey=7b0bbc42ff6a445f86bd907ecccd5618&ip=$host&fields=city";

                $creatorcity = file_get_contents($iptocity);

                $cityy = $this->ambilkata($creatorcity, '"city":"','"}');

                $urltoapi = "https://apichkup.herokuapp.com/chkrdp.php?host=".$host;
                $urltoapi2 = file_get_contents($urltoapi);

                if($countryy == 'Russia') {
                    $countryy = 'Russian Federation';
                }
                $countries = config('country.countries');
                $iso = array_search($countryy, $countries);

                $rdp = Rdp::create([
                    'acctype' => 'RDP',
                    'country' => $iso,
                    'country_full' => $countryy,
                    'city' => $cityy,
                    'infos' => $hostingg,
                    'price' => $request->price,
                    'url' => $url,
                    'sold' => 0,
                    'sto' => null,
                    'sold_date' => null,
                    'access' => $request->access,
                    'windows' => 'Windows'.$request->windows,
                    'ram' => $request->ram,
                    'seller_id' => Auth::user()->seller_id,
                    'reported' => null,
                    'source' => $request->source
                ]);

                $rdps_cnt = Rdp::where('seller_id', Auth::user()->seller_id)->count();

                return response()->json([
                    'msg' => 'success',
                    'rdp' => $rdp,
                    'rdps_cnt' => $rdps_cnt
                ]);
            }
        }
    }

    public function rdp_mass_save(Request $request)
    {
        $urls = explode("\n", $request->host);
        $exist_hosts = array();
        $add_hosts = array();
        $not_working_hosts = array();

        foreach($urls as $url) {
            $new_url = explode('|', $url);
            $host = $new_url[0];
            $username = $new_url[1];
            $password = $new_url[2];

            $isempty_url = Rdp::where('url', $url)->count();

            if($isempty_url > 0) {
                array_push($exist_hosts, $url);
            } else {
                $urltoapi = "https://apichkup.herokuapp.com/chkrdp.php?host=".$host;
                $urltoapi2 = file_get_contents($urltoapi);
                if(preg_match('#bad#', $urltoapi2)){
                    array_push($not_working_hosts, $url);
                } else {
                    $iptohosting = "http://api.ipgeolocation.io/ipgeo?apiKey=7b0bbc42ff6a445f86bd907ecccd5618&ip=$host&fields=isp";

                    $creatorhosting = file_get_contents($iptohosting);

                    $hostingg = $this->ambilkata($creatorhosting, '"isp":"','"}');

                    $iptocountry = "http://api.ipgeolocation.io/ipgeo?apiKey=7b0bbc42ff6a445f86bd907ecccd5618&ip=$host&fields=country_name";

                    $creatorcountry = file_get_contents($iptocountry);

                    $countryy = $this->ambilkata($creatorcountry, '"country_name":"','"}');

                    $iptocity = "http://api.ipgeolocation.io/ipgeo?apiKey=7b0bbc42ff6a445f86bd907ecccd5618&ip=$host&fields=city";

                    $creatorcity = file_get_contents($iptocity);

                    $cityy = $this->ambilkata($creatorcity, '"city":"','"}');

                    $urltoapi = "https://apichkup.herokuapp.com/chkrdp.php?host=".$host;
                    $urltoapi2 = file_get_contents($urltoapi);

                    if($countryy == 'Russia') {
                        $countryy = 'Russian Federation';
                    }
                    $countries = config('country.countries');
                    $iso = array_search($countryy, $countries);

                    $rdp = Rdp::create([
                        'acctype' => 'RDP',
                        'country' => $iso,
                        'country_full' => $countryy,
                        'city' => $cityy,
                        'infos' => $hostingg,
                        'price' => $request->price,
                        'url' => $url,
                        'sold' => 0,
                        'sto' => null,
                        'sold_date' => null,
                        'access' => $request->access,
                        'windows' => 'Windows'.$request->windows,
                        'ram' => $request->ram,
                        'seller_id' => Auth::user()->seller_id,
                        'reported' => null,
                        'source' => $request->source
                    ]);

                    array_push($add_hosts, $rdp);
                }
            }
        }
        $rdps_cnt = Rdp::where('seller_id', Auth::user()->seller_id)->count();

        return response()->json([
            'msg' => 'success',
            'add_hosts' => $add_hosts,
            'exist_hosts' => $exist_hosts,
            'not_working_hosts' => $not_working_hosts,
            'rdps_cnt' => $rdps_cnt
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

    public function shell_show()
    {
        $shells = Stuf::where('seller_id', Auth::user()->seller_id)->get();
        $unsold_cnt = Stuf::where('seller_id', Auth::user()->seller_id)->where('sold', 0)->count();
        $sold_cnt = Stuf::where('seller_id', Auth::user()->seller_id)->where('sold', 1)->count();
        $deleted_cnt = Stuf::where('seller_id', Auth::user()->seller_id)->where('sold', 2)->count();
        return view('pages.seller.management.shell')->with([
            'shells' => $shells,
            'unsold_cnt' => $unsold_cnt,
            'sold_cnt' => $sold_cnt,
            'deleted_cnt' => $deleted_cnt
        ]);
    }

    public function shell_save(Request $request)
    {
        $url = $request->shell_host;
        $o2     = parse_url($url);
        $o = preg_replace('#^www\.(.+\.)#i', '$1', $o2['host']);

        $is_exist = Stuf::where('domain', $o)->where('sold', 0)->count();

        if($is_exist > 0) {
            return response()->json([
                'msg' => 'host exist'
            ]);
        } else {
            $hosting = file_get_contents($url);
            // die(print_r(preg_match('#Client IP:#', $hosting)));

            if (preg_match('#Client IP:#', $hosting) == 1) {

                if (preg_match("#https#",$hosting)){
                    $chkSSL = 'HTTPS';
                } else {
                    $chkSSL = 'HTTP';
                }


                $oip = gethostbyname($o);
                $iptohosting     = "https://api.ipgeolocation.io/ipgeo?apiKey=7b0bbc42ff6a445f86bd907ecccd5618&ip=$oip&fields=isp";
                $creatorhosting  = file_get_contents($iptohosting);

                $hostingg        = $this->ambilkata($creatorhosting, '"isp":"', '"}');

                $infos = $this->ambilkata($hosting,'Cwd:</span></td><td><nobr>','<br>');

                $hostingdetect   = $this->ambilkata($hosting, '</span></td><td>:<nobr>', '<a href="http://www.google.com');

                $iptolocation    = "http://api.ipstack.com/$o?access_key=c81ace46b6108f34ea84dc16bd9d799d&fields=country_name";
                $creatorlocation = file_get_contents($iptolocation);
                // die(print_r($creatorlocation));
                $country         = $this->ambilkata($creatorlocation, '{"country_name":"', '"}');
                if($country == 'Russia') {
                    $country = 'Russian Federation';
                }
                $countries = config('country.countries');
                $iso = array_search($country, $countries);
                // die(print_r($o2['host']));
                $domain = $o2['host'];

                $shell = Stuf::create([
                    'acctype' => 'Shell',
                    'country' => $iso,
                    'country_full' => $country,
                    'infos' => $infos,
                    'url' => $url,
                    'price' => $request->price,
                    'seller_id' => Auth::user()->seller_id,
                    'sold' => 0,
                    'sold_date' => null,
                    'reported' => null,
                    'sto' => null,
                    'domain' => $domain,
                    'hosting_detected' => $hostingg,
                    'ssl_status' => $chkSSL,
                    'source' => $request->source
                ]);

                $shell_cnt = Stuf::where('seller_id', Auth::user()->seller_id)->count();

                return response()->json([
                    'msg' => 'success',
                    'shell' => $shell,
                    'shell_cnt' => $shell_cnt
                ]);
            } else {
                return response()->json([
                    'msg' => 'not working'
                ]);
            }
        }
    }

    public function curl_get_contents($url)
    {
      $curl = curl_init($url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
      $data = curl_exec($curl);
      curl_close($curl);
      return $data;

    }

    public function shell_mass_save(Request $request)
    {
        $urls = explode("\n", $request->shell_mass_host);
        $exist_hosts = array();
        $add_hosts = array();
        $not_working_hosts = array();

        foreach($urls as $url)
        {

            $o2     = parse_url($url);
            $o = preg_replace('#^www\.(.+\.)#i', '$1', $o2['host']);
            $is_exist = Stuf::where('domain', $o)->where('url', $url)->count();

            if($is_exist == 0) {
                if (preg_match("#https#",$url)){
                    $chkSSL = 'HTTPS';
                } else {
                    $chkSSL = 'HTTP';
                }
                $hosting = $this->curl_get_contents($url);
                if (preg_match('#Client IP:#', $hosting) == 1) {

                    $oip = gethostbyname($o);
                    $infos = $this->ambilkata($hosting,'Cwd:</span></td><td><nobr>','<br>');
                    $iptohosting = "https://api.ipgeolocation.io/ipgeo?apiKey=7b0bbc42ff6a445f86bd907ecccd5618&ip=$oip&fields=isp";
                    $creatorhosting  = file_get_contents($iptohosting);
                    $hostingg = $this->ambilkata($creatorhosting, '"isp":"', '"}');
                    $o2     = parse_url($url);
                    $o = preg_replace('#^www\.(.+\.)#i', '$1', $o2['host']);
                    $iptolocation = "http://api.ipstack.com/$o?access_key=c81ace46b6108f34ea84dc16bd9d799d&fields=country_name";
                    $creatorlocation = file_get_contents($iptolocation);
                    $country = $this->ambilkata($creatorlocation,'{"country_name":"','"}');
                    if($country == 'Russia') {
                        $country = 'Russian Federation';
                    }
                    $countries = config('country.countries');
                    $iso = array_search($country, $countries);

                    $shell = Stuf::create([
                        'acctype' => 'Shell',
                        'country' => $iso,
                        'country_full' => $country,
                        'infos' => $infos,
                        'url' => $url,
                        'price' => $request->mass_price,
                        'seller_id' => Auth::user()->seller_id,
                        'sold' => 0,
                        'sold_date' => null,
                        'reported' => null,
                        'sto' => null,
                        'domain' => $o2['host'],
                        'hosting_detected' => $hostingg,
                        'ssl_status' => $chkSSL,
                        'source' => $request->mass_source
                    ]);

                    array_push($add_hosts, $shell);
                } else {
                    array_push($not_working_hosts, $url);
                }

            } else {
                array_push($exist_hosts, $url);
            }
        }

        $shell_cnt = Stuf::where('seller_id', Auth::user()->seller_id)->count();

        return response()->json([
            'msg' => 'success',
            'add_hosts' => $add_hosts,
            'shell_cnt' => $shell_cnt,
            'exist_hosts' => $exist_hosts,
            'not_working_hosts' => $not_working_hosts
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

    public function cpanel_show()
    {
        $cpanels = Cpanel::where('seller_id', Auth::user()->seller_id)->get();
        $unsold_cnt = Cpanel::where('seller_id', Auth::user()->seller_id)->where('sold', 0)->count();
        $sold_cnt = Cpanel::where('seller_id', Auth::user()->seller_id)->where('sold', 1)->count();
        $deleted_cnt = Cpanel::where('seller_id', Auth::user()->seller_id)->where('sold', 2)->count();

        return view('pages.seller.management.cpanel')->with([
            'cpanels' => $cpanels,
            'unsold_cnt' => $unsold_cnt,
            'sold_cnt' => $sold_cnt,
            'deleted_cnt' => $deleted_cnt
        ]);
    }

    public function cpanel_save(Request $request)
    {
        $cpanel_host = $request->cpanel_host;
        $username = $request->cpanel_username;
        $password = $request->cpanel_password;

        $url = $cpanel_host.'|'.$username.'|'.$password;
        $is_exist = Cpanel::where('sold', 0)->where('url', $url)->count();
        if($is_exist > 0) {
            return response()->json([
                'msg' => 'host exist'
            ]);
        } else {
            $host = parse_url($cpanel_host, PHP_URL_HOST);
            $urltoapi = "https://apichkup.herokuapp.com/chkcp.php?cp12=".base64_encode($host)."&login=$username&pass=".rawurlencode($password)."";
            $urltoapi2 = file_get_contents($urltoapi);

            if (preg_match('#CP Work#', $urltoapi2)){
                    // check SSL Status
                    if (preg_match("#https#",$url)){
                        $chkSSL = 'HTTPS';
                    } else {
                        $chkSSL = 'HTTP';
                    }
                    $o = parse_url($cpanel_host)['host'];
                    $oip = gethostbyname($o);
                    $iptohosting = "https://api.ipgeolocation.io/ipgeo?apiKey=7b0bbc42ff6a445f86bd907ecccd5618&ip=$oip&fields=isp";
                    $creatorhosting = file_get_contents($iptohosting);
                    $hostingg = $this->ambilkata($creatorhosting, '{"ip":"'.$oip.'","isp":"', '"}');
                    $iptolocation = "http://api.ipstack.com/$o?access_key=c81ace46b6108f34ea84dc16bd9d799d&fields=country_name";
                    $creatorlocation = file_get_contents($iptolocation);
                    $country = $this->ambilkata($creatorlocation, '{"country_name":"', '"}');
                    // die(print_r($country));
                    if($country == 'Russia') {
                        $country = 'Russian Federation';
                    }
                    $countries = config('country.countries');
                    $iso = array_search($country, $countries);
                    $cpanel = Cpanel::create([
                        'acctype' => 'cPanel',
                        'country' => $iso,
                        'country_full' => $country,
                        'infos' => $hostingg,
                        'url' => $url,
                        'price' => $request->price,
                        'sold' => 0,
                        'sto' => null,
                        'sold_date' => null,
                        'seller_id' => Auth::user()->seller_id,
                        'reported' => null,
                        'ssl_status' => $chkSSL,
                        'source' => $request->source,
                    ]);

                    $cpanel_cnt = Cpanel::where('seller_id', Auth::user()->seller_id)->count();
                    return response()->json([
                        'msg' => 'success',
                        'cpanel' => $cpanel,
                        'cpanel_cnt' => $cpanel_cnt
                    ]);
            } else {
                // die(print_r('not working'));
                return response()->json([
                    'msg' => 'not working'
                ]);
            }
        }
    }

    public function cpanel_mass_save(Request $request)
    {
        $urls = explode("\n", $request->cpanel_mass_host);
        $exist_hosts = array();
        $add_hosts = array();
        $not_working_hosts = array();

        foreach($urls as $url) {
            $url_arr = explode('|', $url);
            $host = $url_arr[0];
            $username = $url_arr[1];
            $password = $url_arr[2];

            $is_exist = Cpanel::where('sold', 0)->where('url', $url)->count();
            if($is_exist > 0) {
                array_push($exist_hosts, $url);
            } else {
                $host = parse_url($host, PHP_URL_HOST);
                $urltoapi = "https://apichkup.herokuapp.com/chkcp.php?cp12=".base64_encode($host)."&login=$username&pass=".rawurlencode($password)."";
                $urltoapi2 = file_get_contents($urltoapi);

                // die(print_r($urltoapi2));

                if (preg_match('#CP Work#', $urltoapi2)){
                        // check SSL Status
                        if (preg_match("#https#",$url)){
                            $chkSSL = 'HTTPS';
                        } else {
                            $chkSSL = 'HTTP';
                        }
                        $o = $host;
                        $oip = gethostbyname($o);
                        $iptohosting = "https://api.ipgeolocation.io/ipgeo?apiKey=7b0bbc42ff6a445f86bd907ecccd5618&ip=$oip&fields=isp";
                        $creatorhosting = file_get_contents($iptohosting);
                        $hostingg = $this->ambilkata($creatorhosting, '{"ip":"'.$oip.'","isp":"', '"}');
                        $iptolocation = "http://api.ipstack.com/$o?access_key=c81ace46b6108f34ea84dc16bd9d799d&fields=country_name";
                        $creatorlocation = file_get_contents($iptolocation);
                        $country = $this->ambilkata($creatorlocation, '{"country_name":"', '"}');
                        // die(print_r($country));
                        if($country == 'Russia') {
                            $country = 'Russian Federation';
                        }
                        $countries = config('country.countries');
                        $iso = array_search($country, $countries);
                        $cpanel = Cpanel::create([
                            'acctype' => 'cPanel',
                            'country' => $iso,
                            'country_full' => $country,
                            'infos' => $hostingg,
                            'url' => $url,
                            'price' => $request->mass_price,
                            'sold' => 0,
                            'sto' => null,
                            'sold_date' => null,
                            'seller_id' => Auth::user()->seller_id,
                            'reported' => null,
                            'ssl_status' => $chkSSL,
                            'source' => $request->source,
                        ]);

                        array_push($add_hosts, $cpanel);

                } else {
                    // die(print_r('not working'));
                    array_push($not_working_hosts, $url);
                }
            }
        }
        $cpanel_cnt = Cpanel::where('seller_id', Auth::user()->seller_id)->count();
        return response()->json([
            'msg' => 'success',
            'cpanel_cnt' => $cpanel_cnt,
            'exist_hosts' => $exist_hosts,
            'add_hosts' => $add_hosts,
            'not_working_hosts' => $not_working_hosts
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

    public function phpmailer_show()
    {
        $mailers = Mailer::where('seller_id', Auth::user()->seller_id)->get();
        $unsold_cnt = Mailer::where('seller_id', Auth::user()->seller_id)->where('sold', 0)->count();
        $sold_cnt = Mailer::where('seller_id', Auth::user()->seller_id)->where('sold', 1)->count();
        $deleted_cnt = Mailer::where('seller_id', Auth::user()->seller_id)->where('sold', 2)->count();

        return view('pages.seller.management.phpmailer')->with([
            'mailers' => $mailers,
            'unsold_cnt' => $unsold_cnt,
            'sold_cnt' => $sold_cnt,
            'deleted_cnt' => $deleted_cnt
        ]);
    }

    public function mailer_save(Request $request)
    {
        $mailer_hosts = $request->mailer_host;
        $source = $request->source;
        $price = $request->price;

        $exist_hosts = array();
        $add_hosts = array();
        $not_working_hosts = array();

        $hosts = explode("\n", $mailer_hosts);
        foreach($hosts as $host) {
            $is_exist = Mailer::where('url', $host)->where('sold', 0)->count();

            if($is_exist > 0) {
                array_push($exist_hosts, $host);
            } else {
                if (preg_match("#https#",$host)){
                    $chkSSL = 'HTTPS';
                } else {
                    $chkSSL = 'HTTP';
                }
                $hosting=file_get_contents($host);

			    if (preg_match('#Server IP Address :#', $hosting)) {

                    $o2     = parse_url($host);
                    $o = preg_replace('#^www\.(.+\.)#i', '$1', $o2['host']);
                    $oip = gethostbyname($o);
                    $iptohosting = "https://api.ipgeolocation.io/ipgeo?apiKey=7b0bbc42ff6a445f86bd907ecccd5618&ip=$oip&fields=isp";
                    $creatorhosting = file_get_contents($iptohosting);
                    $hostingg = $this->ambilkata($creatorhosting, '"isp":"','"}');
                    $hostingdetect = $this->ambilkata($hosting,'</span></td><td>:<nobr>','<a href="http://www.google.com');

                    $iptolocation = "http://api.ipstack.com/$o?access_key=c81ace46b6108f34ea84dc16bd9d799d&fields=country_name";
                    $creatorlocation = file_get_contents($iptolocation);
                    $country = $this->ambilkata($creatorlocation,'{"country_name":"','"}');
                    if($country == 'Russia') {
                        $country = 'Russian Federation';
                    }
                    $countries = config('country.countries');
                    $iso = array_search($country, $countries);
                    $mailer = Mailer::create([
                        'acctype' => 'Mailer',
                        'country' => $iso,
                        'country_full' => $country,
                        'infos' => $hostingg,
                        'url' => $host,
                        'price' => $price,
                        'sold' => 0,
                        'sto' => null,
                        'sold_date' => null,
                        'seller_id' => Auth::user()->seller_id,
                        'reported' => null,
                        'ssl_status' => $chkSSL,
                        'source' => $source,
                    ]);

                    array_push($add_hosts, $mailer);
                } else {
                    array_push($not_working_hosts, $host);
                }
            }
        }

        $mailer_cnt = Mailer::where('seller_id', Auth::user()->seller_id)->count();
        return response()->json([
            'msg' => 'success',
            'mailer_cnt' => $mailer_cnt,
            'exist_hosts' => $exist_hosts,
            'add_hosts' => $add_hosts,
            'not_working_hosts' => $not_working_hosts
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

    public function smtp_show()
    {
        $smtps = Smtp::where('seller_id', Auth::user()->seller_id)->get();
        $unsold_cnt = Smtp::where('seller_id', Auth::user()->seller_id)->where('sold', 0)->count();
        $sold_cnt = Smtp::where('seller_id', Auth::user()->seller_id)->where('sold', 1)->count();
        $deleted_cnt = Smtp::where('seller_id', Auth::user()->seller_id)->where('sold', 2)->count();

        return view('pages.seller.management.smtp')->with([
            'smtps' => $smtps,
            'unsold_cnt' => $unsold_cnt,
            'sold_cnt' => $sold_cnt,
            'deleted_cnt' => $deleted_cnt
        ]);
    }

    public function srl($item){

        $item0 = $item;
        $item1 = rtrim($item0);
        $item2 = ltrim($item1);
        return $item2;
    }

    public function smtp_save(Request $request)
    {
        $urls = explode("\n", $request->smtp_host);
        $source = $request->source;
        $price = $request->price;

        $exist_hosts = array();
        $add_hosts = array();
        $not_working_hosts = array();

        foreach($urls as $url) {
            $url_arr = explode('|', $url);
            $host = $this->srl($url_arr[0]);
            $port = $this->srl($url_arr[1]);
            $username = $this->srl($url_arr[2]);
            $password = $this->srl($url_arr[3]);
            $pass_encode = rawurlencode($password);
            $testemail = 'hrm.2021@outlook.com';
            // die(print_r($testemail));

            $is_exist = Smtp::where('sold', 0)->where('url', $url)->count();

            if($is_exist > 0) {
                array_push($exist_hosts, $url);
            } else {
                $o2     = parse_url($host);

                $o = preg_replace('#^www\.(.+\.)#i', '$1', $host);
                $oip = gethostbyname($o);

                $sitetoip = "http://api.ipstack.com/$oip?access_key=f991d31642a29f8a8197b57ef76f167b&fields=ip";

                $creatorip = file_get_contents($sitetoip);
                $ipsss = $this->ambilkata($creatorip, '{"ip":"','"}');

                $iptohosting = "https://api.ipgeolocation.io/ipgeo?apiKey=fd31aec986094e1281251605a9bf5a5e&ip=$ipsss&fields=isp";

                $creatorhosting = file_get_contents($iptohosting);

                $hostingg = $this->ambilkata($creatorhosting, '"isp":"','"}');

                $iptocountry = "http://api.ipstack.com/$oip?access_key=f991d31642a29f8a8197b57ef76f167b&fields=country_name";

                $creatorcountry = file_get_contents($iptocountry);

                $countryy = $this->ambilkata($creatorcountry, '{"country_name":"','"}');

                if($countryy == 'Russia') {
                    $countryy = 'Russian Federation';
                }
                $countries = config('country.countries');
                $iso = array_search($countryy, $countries);
                $smtp = Smtp::create([
                    'acctype' => 'SMTP',
                    'country' => $iso,
                    'country_full' => $countryy,
                    'infos' => $hostingg,
                    'url' => $url,
                    'price' => $price,
                    'sold' => 0,
                    'sto' => null,
                    'sold_date' => null,
                    'seller_id' => Auth::user()->seller_id,
                    'reported' => null,
                    'source' => $source,
                ]);

                array_push($add_hosts, $smtp);

            }
        }
        $smtp_cnt = Smtp::where('seller_id', Auth::user()->seller_id)->count();
        return response()->json([
            'msg' => 'success',
            'smtp_cnt' => $smtp_cnt,
            'exist_hosts' => $exist_hosts,
            'add_hosts' => $add_hosts
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

    public function leads_show()
    {
        $leads = Lead::where('seller_id', Auth::user()->seller_id)->get();
        $unsold_cnt = Lead::where('seller_id', Auth::user()->seller_id)->where('sold', 0)->count();
        $sold_cnt = Lead::where('seller_id', Auth::user()->seller_id)->where('sold', 1)->count();
        $deleted_cnt = Lead::where('seller_id', Auth::user()->seller_id)->where('sold', 2)->count();

        return view('pages.seller.management.leads')->with([
            'leads' => $leads,
            'unsold_cnt' => $unsold_cnt,
            'sold_cnt' => $sold_cnt,
            'deleted_cnt' => $deleted_cnt
        ]);
    }

    public function lead_save(Request $request)
    {
        $link = $request->link;
        $number = $request->number;
        $infos = $request->infos;
        $country = $request->country;
        $type = $request->type;
        $price = $request->price;
        $screenshot = $request->screenshot;

        if($type == 'checked_list') {
            $type = '100% Email Checked List';
        } else if($type == 'email_list') {
            $type = 'Email List';
        } else if($type = 'combo_list') {
            $type = 'Combo List';
        }

        $is_exist = Lead::where('url', $link)->where('acctype', $type)->count();
        $countries = config('country.countries');

        if($is_exist > 0 ) {
            return response()->json([
                'msg' => 'link exist'
            ]);
        } else {
            $lead = Lead::create([
                'acctype' => $type,
                'country' => $country,
                'country_full' => $countries[$country],
                'infos' => $infos,
                'url' => $link,
                'screenshot' => $screenshot,
                'price' => $price,
                'seller_id' => Auth::user()->seller_id,
                'sold' => 0,
                'sto' => null,
                'sold_date' => null,
                'number' => $number,
                'reported' => null,
            ]);

            $lead_cnt = Lead::where('seller_id', Auth::user()->seller_id)->count();

            return response()->json([
                'msg' => 'success',
                'lead' => $lead,
                'lead_cnt' => $lead_cnt
            ]);
        }
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

    public function accounts_show(Request $request)
    {
        $accounts = Account::where('seller_id', Auth::user()->seller_id)->get();
        $unsold_cnt = Account::where('seller_id', Auth::user()->seller_id)->where('sold', 0)->count();
        $sold_cnt = Account::where('seller_id', Auth::user()->seller_id)->where('sold', 1)->count();
        $deleted_cnt = Account::where('seller_id', Auth::user()->seller_id)->where('sold', 2)->count();

        return view('pages.seller.management.accounts')->with([
            'accounts' => $accounts,
            'unsold_cnt' => $unsold_cnt,
            'sold_cnt' => $sold_cnt,
            'deleted_cnt' => $deleted_cnt
        ]);
    }

    public function account_save(Request $request)
    {
        $sitename = $request->sitename;
        $acctype = $request->type;
        $infos = $request->infos;
        $country = $request->country;
        $screenshot = $request->screenshot;
        $price = $request->price;
        $url = $request->url;
        $countries = config('country.countries');
        $country_full = $countries[$country];
        $source = $request->source;

        if($acctype == 'marketing') {
            $acctype = 'Marketing';
        } elseif($acctype == 'hosting') {
            $acctype = 'Hosting/Domain';
        } elseif($acctype == 'games') {
            $acctype = 'Games';
        } elseif($acctype == 'vpn') {
            $acctype = 'VPN/Socks Proxy';
        } elseif($acctype == 'shopping') {
            $acctype = 'Shopping {Amazon, eBay .... etc }';
        } elseif($acctype == 'stream') {
            $acctype = 'Stream { Music, Netflix, iptv, HBO, bein sport, WWE ...etc }';
        } elseif($acctype == 'dating') {
            $acctype = 'Dating';
        } elseif($acctype == 'learning') {
            $acctype = 'Learning { udemy, lynda, .... etc. }';
        } elseif($acctype == 'voip') {
            $acctype = 'Voip/Sip';
        }

        $is_exist = Account::where('url', $url)->count();
        if($is_exist > 0) {
            return response()->json([
                'msg' => 'host exists'
            ]);
        } else {
            $account = Account::create([
                'acctype' => $acctype,
                'country' => $country,
                'country_full' => $country_full,
                'infos' => $infos,
                'price' => $price,
                'url' => $url,
                'sold' => 0,
                'sto' => null,
                'sold_date' => null,
                'seller_id' => Auth::user()->seller_id,
                'reported' => null,
                'sitename' => $sitename,
                'screenshot' => $screenshot,
                'source' => $source
            ]);

            $account_cnt = Account::where('seller_id', Auth::user()->seller_id)->count();

            return response()->json([
                'msg' => 'success',
                'account' => $account,
                'account_cnt' => $account_cnt
            ]);
        }
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

    public function tutorials_show(Request $request)
    {
        $tutorials = Tutorial::where('seller_id', Auth::user()->seller_id)->get();

        return view('pages.seller.management.tutorials')->with([
            'tutorials' => $tutorials,
        ]);
    }

    public function tutorial_save(Request $request)
    {
        $link = $request->link;
        $name = $request->name;
        $infos = $request->infos;
        $price = $request->price;
        $screenshot = $request->screenshot;
        $country = $request->country;
        $countries = config('country.countries');
        $country_full = $countries[$country];

        $is_exist = Tutorial::where('url', $link)->count();
        if($is_exist > 0) {
            return response()->json([
                'msg' => 'host exist'
            ]);
        } else {
            $tutorial = Tutorial::create([
                'country' => $country,
                'country_full' => $country_full,
                'acctype' => 'Tutorial / Method',
                'infos' => $infos,
                'url' => $link,
                'tutorial_name' => $name,
                'price' => $price,
                'screenshot' => $screenshot,
                'sold' => 0,
                'sold_date' => null,
                'sto' => null,
                'seller_id' => Auth::user()->seller_id
            ]);

            $tutorial_cnt = Tutorial::where('seller_id', Auth::user()->seller_id)->count();

            return response()->json([
                'msg' => 'success',
                'tutorial' => $tutorial,
                'tutorial_cnt' => $tutorial_cnt
            ]);
        }
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

    public function scams_show(Request $request)
    {
        $scams = Scam::where('seller_id', Auth::user()->seller_id)->get();

        return view('pages.seller.management.scam')->with([
            'scams' => $scams,
        ]);
    }

    public function scam_save(Request $request)
    {
        $link = $request->link;
        $name = $request->name;
        $infos = $request->infos;
        $price = $request->price;
        $screenshot = $request->screenshot;
        $country = $request->country;
        $countries = config('country.countries');
        $country_full = $countries[$country];

        $is_exist = Scam::where('url', $link)->count();
        if($is_exist > 0) {
            return response()->json([
                'msg' => 'host exist'
            ]);
        } else {
            $scam = Scam::create([
                'country' => $country,
                'country_full' => $country_full,
                'acctype' => 'Exploit/Script/ScamPage',
                'infos' => $infos,
                'url' => $link,
                'scam_name' => $name,
                'price' => $price,
                'screenshot' => $screenshot,
                'sold' => 0,
                'sold_date' => null,
                'sto' => null,
                'seller_id' => Auth::user()->seller_id
            ]);

            $scam_cnt = Scam::where('seller_id', Auth::user()->seller_id)->count();

            return response()->json([
                'msg' => 'success',
                'scam' => $scam,
                'scam_cnt' => $scam_cnt
            ]);
        }
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
