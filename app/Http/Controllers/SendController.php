<?php
namespace App\Http\Controllers;

use App\Models\Mailer;
use App\Models\Smtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class SendController extends Controller
{
    public function mailers()
    {
        $mailers = Mailer::where('sold', 0)->orderBy(DB::raw('RAND()'))->get();
        $countries = Mailer::where('sold', 0)->select('country', 'country_full')->distinct()->get();
        $sellers = Mailer::where('sold', 0)->select('seller_id')->distinct()->get();
        return view('pages.buyer.send.mailer')->with([
            'mailers' => $mailers,
            'countries' => $countries,
            'sellers' => $sellers
        ]);
    }

    public function mailer_check(Request $request)
    {
        $mailer_id = $request->mailer_id;
        $mailer = Mailer::where('id', $request->mailer_id)->first();
        $serverurl = $mailer->url;
        $testemail = Auth::user()->test_email;
		$ch = curl_init("$serverurl");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout in second
        curl_setopt($ch, CURLOPT_POSTFIELDS,
        array('senderEmail'=>"noreplay@ozix.to",'senderName'=>'Ozix Shop Test Mailer','subject'=>"Ozix Shop Mailer #$mailer_id - Working!",'messageLetter'=>"
        Mailer Work Your Check ID is: #$mailer_id
        ",'emailList'=>"$testemail",'action'=>'send'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $postResult = curl_exec($ch);
        curl_close($ch);
        if(preg_match('#<span class="label label-success">Ok</span></div><br>#',$postResult)){
            $msg = 'working';
        }  elseif(preg_match('#<span class="label label-default">Incorrect Email</span>#',$postResult))  {
            $msg = 'incorrect email';
        } else {
            $msg = 'not working';
            Mailer::where('id', $mailer_id)->update([
                'sold' => 2
            ]);
        }

        return response()->json([
            'msg' => $msg
        ]);
    }

    public function change_test_email(Request $request)
    {
        $test_email = $request->test_email;
        User::where('id', Auth::user()->id)->update([
            'test_email' => $test_email
        ]);

        return response()->json([
            'msg' => 'success'
        ]);
    }

    public function mailer_filter(Request $request)
    {
        $infos = $request->infos;
        $country = $request->country;
        $source = $request->source;
        $seller = $request->seller;
        $min_price = $request->min_price;
        $max_price = $request->max_price;

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
                $mailers = Mailer::where('infos', 'like', $infos.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('source', 'like', $source.'%')
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->get();
            } else {
                $mailers = Mailer::where('infos', 'like', $infos.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('source', 'like', $source.'%')
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->get();
            }


        } else {
            if($max_price != 0) {
                $mailers = Mailer::where('infos', 'like', $infos.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('source', 'like', $source.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->get();
            } else {
                $mailers = Mailer::where('infos', 'like', $infos.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('source', 'like', $source.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->get();
            }

        }

        return response()->json([
            'mailers' => $mailers
        ]);
    }

    public function smtps()
    {
        $smtps = Smtp::where('sold', 0)->orderBy(DB::raw('RAND()'))->get();
        $countries = Smtp::where('sold', 0)->select('country', 'country_full')->distinct()->get();
        $sellers = Smtp::where('sold', 0)->select('seller_id')->distinct()->get();
        return view('pages.buyer.send.smtp')->with([
            'smtps' => $smtps,
            'countries' => $countries,
            'sellers' => $sellers
        ]);
    }

    public function smtp_filter(Request $request)
    {
        $infos = $request->infos;
        $country = $request->country;
        $source = $request->source;
        $seller = $request->seller;
        $min_price = $request->min_price;
        $max_price = $request->max_price;

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
                $smtps = Smtp::where('infos', 'like', $infos.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('source', 'like', $source.'%')
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->get();
            } else {
                $smtps = Smtp::where('infos', 'like', $infos.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('source', 'like', $source.'%')
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->get();
            }


        } else {
            if($max_price != 0) {
                $smtps = Smtp::where('infos', 'like', $infos.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('source', 'like', $source.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('price', '<=', $max_price)
                            ->where('sold', 0)
                            ->get();
            } else {
                $smtps = Smtp::where('infos', 'like', $infos.'%')
                            ->where('country', 'like', $country.'%')
                            ->where('source', 'like', $source.'%')
                            ->where('seller_id', $seller)
                            ->where('price', '>=', $min_price)
                            ->where('sold', 0)
                            ->get();
            }

        }

        return response()->json([
            'smtps' => $smtps
        ]);
    }

    public function srl($item){

        $item0 = $item;
        $item1 = rtrim($item0);
        $item2 = ltrim($item1);
        return $item2;
    }

    public function smtp_check(Request $request)
    {
        $smtp_id = $request->smtp_id;
        $smtp = Smtp::where('id', $smtp_id)->first();
        $serverurl = $smtp->url;
        $d      = explode("|", $serverurl);
        $url    = $this->srl($d[0]);
        $login  = $this->srl($d[2]);
        $pass   = $this->srl($d[3]);
        $port   = $this->srl($d[1]);
        $testemail = Auth::user()->test_email;
        $pass_encode = rawurlencode($pass);

        $urltoapi  = "https://apichkup.herokuapp.com/chksmtp.php?host=$url&login=$login&pass=$pass_encode&port=$port&id=$smtp_id&testmail=$testemail&subject=ozix";
        $urltoapi = file_get_contents($urltoapi);
        $serverurl = $urltoapi;

        // $mail = new PHPMailer();

        // // //$mail->SMTPDebug = 3;                               // Enable verbose debug output
        // // die(print_r($pass));
        // $mail->IsSMTP();
        // $mail->Mailer = "smtp";                          // Set mailer to use SMTP
        // $mail->Host = 'smtp.'.$url;  // Specify main and backup SMTP servers
        // $mail->SMTPAuth = true;                               // Enable SMTP authentication
        // $mail->Username = $login;                 // SMTP username
        // $mail->Password = $pass_encode;                           // SMTP password
        // if($port == 587) {
        //     $secure = 'tls';
        // } else {
        //     $secure = 'ssl';
        // }
        // $mail->SMTPSecure = $secure;                            // Enable TLS encryption, `ssl` also accepted
        // $mail->Port = $port;                                    // TCP port to connect to

        // $mail->From = 'noreply@ozix.to';
        // $mail->addAddress($testemail);     // Add a recipient

        // $mail->isHTML(true);                                  // Set email format to HTML

        // $mail->Subject = 'SMTP #'.$smtp_id.' test';
        // $mail->Body    = 'SMTP #'.$smtp_id.' is working.';
        // $mail->send();
        // if(!$mail->send()) {
        //     echo 'Message could not be sent.';
        //     echo 'Mailer Error: ' . $mail->ErrorInfo;
        // } else {
        //     echo 'Message has been sent';
        // }
        // die(print_r($serverurl));
        if (preg_match('#Message sent!#', $serverurl)) {
            $msg = 'working';
        } else {
            $msg = 'not working';
            Smtp::where('id', $smtp_id)->update([
                'sold' => 2
            ]);
        }

        return response()->json([
            'msg' => $msg
        ]);
    }
}
