<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\EmailVerification;
use App\Models\User;

class EmailVerificationMiddleware
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

        $user = User::where('email', '=', $request['email'])->first();
        #dd($user);
        if($user != Null && !$user->verified)
        {
            return response()->json("you're not verified, check your email", 300);

            /*redirect()
                    ->route('login')
                    ->with('message', 'You need to confirm your account. We have sent you an activation code, please check your email.');
*/
        }
        #return False;
        return $next($request);
    }
}
