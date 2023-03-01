<?php

namespace App\Http\Middleware;

use App\Models\RegistrationPayment;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $lang = "";
        if ($lang == '') {
            $lang = env('DEFAULT_LANG') ?? 'en';
        }

        \App::setLocale($lang);
        $danger = 'Oops! you have supplied invalid credentials please try again or contact admin!';

        $user = User::where('email',$request->email)->first();

        if ($user){
            if($user->type == 'admin' || $user->type == 'user') //check if its verified
            {
                return $next($request);
            }
            else
            {
                return response()->view('auth.login', compact('lang', 'danger'));
            }
        }
        return response()->view('auth.login', compact('lang', 'danger'));

    }
}
