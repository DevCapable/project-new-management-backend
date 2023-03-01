<?php

namespace App\Http\Middleware;

use App\Models\Client;
use App\Models\RegistrationPayment;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentRegMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $lang = "";
        if ($lang == '') {
            $lang = env('DEFAULT_LANG') ?? 'en';
        }

        \App::setLocale($lang);
        $danger = 'Oops! you have supplied invalid credentials please try again or click on Register below';
        $info = 'Please note that you have to make payment to complete your registration ';


        $client = Client::where('email', $request->email)->first();


        $warning = 'Your registration was not completed please proceed';
        if ($client == null) {

            return response()->view('auth.client_login', compact('lang', 'client', 'danger'));
        } elseif ($client) {
            $payment = RegistrationPayment::where('user_id', $client->id)->first();

            if ($payment) {
                return $next($request);
            } elseif ($client->is_verified !== 1) {
                $warning = 'You have to verify your email account first, please check your email for verification';

                return response()->view('auth.login', compact('lang', 'client', 'warning'));
            }
        } elseif ($client->from_admin == 1) {

            return response()->view('auth.client_login', compact('lang', 'client', 'info'));
        } else {
            return response()->view('auth._client_reg_payment', compact('lang', 'client', 'warning'));
        }
        return response()->view('auth.login', compact('lang', 'client', 'info'));
    }
}
