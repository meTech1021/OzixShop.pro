<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function signin(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        if($validate)
        {
            $credentials = $request->only('email', 'password');

            if(Auth::attempt($credentials, $request->has('remember')))
            {

                $request->session()->regenerate();
                User::where('email', $request->email)->update([
                    'last_login_at' => date('y-m-d h:i:s')
                ]);
                $user = User::where('email', $request->email)->first();

                if($user->role == 1) {
                    return redirect('/admin');
                } elseif($user->role == 2) {
                    return redirect('/seller');
                } elseif($user->role == 3) {
                    return redirect('/home');
                }

            } else {
                $user = User::where('email', $request->email)->get();
                if(count($user) > 0)
                {
                    if(!Hash::check($request->password, $user[0]->password))
                    {
                        return redirect()->back()->withErrors([
                            'error' => 'Please enter your information exactly.',
                            'password' => 'Please enter password exactly.',
                            'old_email' => $request->email
                        ]);
                    }
                } else {
                    return redirect()->back()->withErrors([
                        'error' => 'Please enter your information exactly.',
                        'email' => 'Please enter email exactly.',
                        'password' => 'Please enter password exactly.'
                    ]);
                }

            }
        }
    }

    public function register()
    {
        return view('auth.register');
    }

    public function signup(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|min:6|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);
        if($validate)
        {
            $data = $request->all();
            if($this->create($data))
            {
                $credentials = $request->only('email', 'password');

                if(Auth::attempt($credentials, $request->has('remember')))
                {

                    $request->session()->regenerate();
                    User::where('email', $request->email)->update([
                        'last_login_at' => date('y-m-d h:i:s')
                    ]);
                    $user = User::where('email', $request->email)->first();

                    if($user->role == 1) {
                        return redirect('/admin');
                    } elseif($user->role == 2) {
                        return redirect('/seller');
                    } elseif($user->role == 3) {
                        return redirect('/home');
                    }
                }
            }
        }

    }

    public function create(array $data)
    {
        return User::create([
            'name'=> $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'balance' => 0,
            'ipurchassed' => 0,
            'ip' => getenv("REMOTE_ADDR"),
            'test_email' => $data['email'],
            'reset_pin' => null,
            'ref' => 'N/A',
            'refrewards' => 0
        ]);
    }

    public function forgot_password()
    {
        return view('auth.forgot');
    }

    public function reset_password(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $token = Str::random(60);

        $email = $request->email;
        $user = User::where('email', $email)->first();
        if($user) {
            $user = DB::table('password_resets')->where('email', $email)->first();
            if($user) {
                DB::table('password_resets')->where('email', $email)->update([
                    'token' => $token
                ]);
            } else {
                DB::table('password_resets')->insert([
                    'email' => $email,
                    'token' => $token
                ]);
            }
            $to = $request->email;
            $subject = "Reset Password";

            $message = "
            <html>
            <head>
            <title>Reset Password</title>
            </head>
            <body>
            <p>This is your token to reset password on ozix.to</p>
            <h4>".$token."</h4>
            </body>
            </html>
            ";

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: <noreply@ozix.to>' . "\r\n";

            mail($to,$subject,$message,$headers);
            return redirect('/auth/resetpassword');
        } else {
            return redirect()->back()->withErrors([
                'error' => 'This email is not exist.'
            ]);
        }
    }

    public function reset()
    {
        return view('auth.reset');
    }

    public function reset_post(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = DB::table('password_resets')->where('token', $request->token)->first();

        if($user) {
            User::where('email', $user->email)->update([
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            return redirect('/auth/login');
        } else {
            return redirect()->back()->withErrors([
                'token' => 'Invalid token'
            ]);
        }
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }


}
