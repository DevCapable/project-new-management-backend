<?php

namespace App\Http\Middleware;

use App\Models\Client;
use App\Models\RegistrationPayment;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerification
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
        $client = Client::where('email',$request->email)->first();

         $warning = 'Your registration was not completed, please verify your email and you can as well re-register with the same email to proceed on your verification';
           if ($client){
               if($client->is_verified == 1 || $client->type == 'admin' || $client->type == 'client') //check if its verified
               {
                   return $next($request);
               }
               else
               {
                   return response()->view('auth.register', compact('lang', 'warning'));
               }

           }
        return response()->view('auth.register', compact('lang', 'warning'));

    }
}
