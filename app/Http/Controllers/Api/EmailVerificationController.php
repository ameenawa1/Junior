<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Support\Facades\Validator;



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


        $data = $request->all();

        $validator = Validator::make($data,[
            "verification_code" => "required|numeric|digits:5",
            "email" => "required|string|email|max:25",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $request_code = $data['verification_code'];

        $user = User::where('email', '=', $data['email'])->first();


        if(!$user)
        {
            return response()->json("Error, user not found.");
        }


        $correctcode = EmailVerification::where('user_id', '=', $user['id'])->first();

        if(! $correctcode)
        {
            return response()->json("Error proccessing code, please request a new verification code.");
        }

        if($user['verified'] == 1){

            return response()->json("You're verified gtfo.");
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
            response()->json('u fucked up and now imma die :( *dies*', 300)->send();
            die;
        }
    }
}
