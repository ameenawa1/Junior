<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailVerification;
use App\Models\User;


class EmailVerificationController extends Controller
{

    public function check($email)
    {
        #validate $email
        #dd($email);
        $user = User::where('email', '=', $email)->first();

        #dd($user->verified);
        if($user != null && $user->verified == 1)
        {

            return true;
        }
        return false;
    }

    public function verify(Request $request){  #edit idea: generate code and send it to application and email instead of saving in db

        $request_code = $request['verification_code'];

        $user = User::where('email', '=', $request['email'])->first();

        $correctcode = EmailVerification::where('user_id', '=', $user['id'])->first();



        if($user['verified'] == 1){

            response("You're verified gtfo.")->send();
            return die;
        }

        if(!$request_code || !$user || !$correctcode)
        {
            response('something is null and now imma die :( *dies* ')->send();
            die;
        }

        if($request_code == $correctcode['code'])
        {
            $user['verified'] = 1;
            $user->save();
            EmailVerification::find($correctcode['id'])->delete();
            return response()->json('User verified', 200);
        }
        else
        {
            response()->json('now imma die, u fucked up *dies*', 300)->send();
            die;
        }
    }
}
