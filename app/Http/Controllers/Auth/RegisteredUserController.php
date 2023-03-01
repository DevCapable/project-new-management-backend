<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\clientFormRequest;
use App\Http\Requests\Auth\taskFormRequest;
use App\Mail\RegistrationEmail;
use App\Models\Client;
use App\Models\ClientWorkspace;
use App\Models\PaymentLists;
use App\Models\RegistrationPayment;
use App\Models\User;
use App\Models\Workspace;
use App\Models\UserWorkspace;
use App\Providers\RouteServiceProvider;
use Complex\Exception;
use Illuminate\Auth\Events\Registered;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */


    public function __construct()
    {
        // $this->middleware('guest');
    }


    public function create()
    {
        // return view('auth.register');
    }


    /**
     * Handle an incoming registration request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'workspace' => 'required', 'string', 'max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', 'min:8', 'confirmed', Rules\Password::defaults()],
        ]);
        if (env('RECAPTCHA_MODULE') == 'on') {
            $validation['g-recaptcha-response'] = 'required|captcha';
        } else {
            $validation = [];
        }
        $this->validate($request, $validation);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'workspace' => $request->workspace,
            'plan' => 1,
        ]);


        $objWorkspace = Workspace::create(['created_by' => $user->id, 'name' => $request->workspace, 'currency_code' => 'USD', 'paypal_mode' => 'sandbox']);
        $user->currant_workspace = $objWorkspace->id;
        $user->save();
        event(new Registered($user));

        Auth::login($user);
        UserWorkspace::create(['user_id' => $user->id, 'workspace_id' => $objWorkspace->id, 'permission' => 'Owner']);

        return redirect(RouteServiceProvider::HOME);
    }


    public function showRegistrationForm($lang = '')
    {
        if ($lang == '') {
            $lang = env('DEFAULT_LANG') ?? 'en';
        }

        \App::setLocale($lang);

        if (env('signup_button') == 'on') {
            return view('auth.register', compact('lang'));
        } else {
            return abort('404', 'Page not found');
        }
    }


    public function postClientRegistrationForm(clientFormRequest $request)
    {
        $lang ='';
        if ($lang == '') {
            $lang = env('DEFAULT_LANG') ?? 'en';
        }

        \App::setLocale($lang);
        if (env('RECAPTCHA_MODULE') == 'on') {
            $validation['g-recaptcha-response'] = 'required|captcha';
        } else {
            $validation = [];
        }
        $user = $client = Client::where('email', $request->email)->first();

        if ($user && $user->is_verified == 0) {
            $this->sendSignupEmail($user->name, $user->email, $user->verification_code);
            return view('auth.login',compact('user','lang'))->with('info','Your account had already been created. Please check email for verification link.');
            return redirect()->to('/login')->with(['info'=>'Your account had already been created. Please check email for verification link.',
            'user'=>$user
            ]);
        } else {
            $request->validate(['email' => 'required|string|email|max:255|unique:clients']);
            $request->validated();

            $this->validate($request, $validation);
            $user = new Client();
            $user->name = $request->name;
//            $user->payment_policy = 1;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->current_workspace = $request->workspace;
            $user->verification_code = sha1(time());

            $user->save();
            event(new Registered($user));
//        Auth::login($user);

            if ($user != null) {
                $objWorkspace = Workspace::create(['created_by' => $user->id, 'name' => $request->workspace, 'currency_code' => 'USD', 'paypal_mode' => 'sandbox']);
                $user->current_workspace = $objWorkspace->id;
                ClientWorkspace::create(['client_id' => $user->id, 'workspace_id' => $objWorkspace->id, 'is_active' => 1, 'permission' => 'Owner']);
                $this->sendSignupEmail($user->name, $user->email, $user->verification_code);
//                UserWorkspace::create(['user_id' => $user->id, 'workspace_id' => $objWorkspace->id, 'permission' => 'Owner']);
                return redirect()->to('/login')->with('success', 'Your account has been created. Please check email for verification link.');

            }


//        return redirect(RouteServiceProvider::HOME);
        }

    }


    public function sendSignUpEmail($name, $email, $verification_code)
    {
        $data = [
            'name' => $name,
            'verification_code' => $verification_code
        ];

        try
        {
            Mail::to($email)->send(new RegistrationEmail($data));
        } catch (\Exception $e) {
            $smtp_error = __('E-Mail has not been sent due to SMTP configuration,or error in network connection');
            return redirect()->back()->with('error',$smtp_error);
        }
    }

    public function verifyClientEmail(Request $request, $lang = '')
    {

        if ($lang == '') {
            $lang = env('DEFAULT_LANG') ?? 'en';
        }

        \App::setLocale($lang);
        $verification_code = $request::get('code');

        $update_client = $client = Client::where('verification_code', $verification_code)->first();
        $check = RegistrationPayment::where('user_id', $client->id)->first();

        if (!$update_client) {
            return view('auth.register', compact('lang', 'client'))->with('danger', 'Error! invalid verification code please check latest mail received or you can re-register with a valid email account');
        }

        if ($update_client->is_verified == 0) {
            $update_client->is_verified = 1;
            $update_client->email_verified_at = now();
            $update_client->save();
            if ($check) return redirect()->to('/client/login')->with('success', 'Ho Snaps! you already made registration payment, you can now access your portal!');
            return view('auth._client_reg_payment', compact('lang', 'client'))->with('success', 'Awesome! you have successfully verified your email, please proceed');
        } elseif ($update_client->is_verified == 1) {
            if ($check) return redirect()->to('/client/login')->with('success', 'Ho Snaps! you already made registration payment, you can now access your portal!');
            return view('auth._client_reg_payment', compact('lang', 'client'))->with('warning', 'Ooops! you already verified your email, please proceed');
        } else {
            return redirect()->to('/register')->with('error', 'Ooops! invalid verification code');

        }
    }
    public function clientResendVerificationLink($slug, $client)
    {
        $user = Client::where('id',$client)->first();

        $this->sendSignupEmail($user->name, $user->email, $user->verification_code);
        return redirect()->to('/login')->with('info', 'We have resent your verification link again. Please check your email for verification link.');

    }


    public function showClientPaymentPage($lang = '')
    {
        if ($lang == '') {
            $lang = env('DEFAULT_LANG') ?? 'en';
        }

        \App::setLocale($lang);

        if (env('CLIENT_PAYMENT_PAGE') == 'on') {
            return view('auth._client_reg_payment', compact('lang'));
        } else {
            return abort('404', 'Page not found');
        }
    }

    public function getClientPaymentPage()
    {

    }
}

