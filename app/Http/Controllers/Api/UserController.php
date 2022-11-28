<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Card;
use App\Models\EmailVerification;
use App\Models\PasswordReset;
use App\Http\Controllers\Api\PhoneNumberController;
use App\Http\Controllers\Api\LinkController;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Http\Controllers\MailController;

class UserController extends Controller
{

    public function get_user_by_id($id=null)
    {
        return $id ? User::find($id) : User::all();
    }


    public function request_password_change(Request $request)
    {
        $data =$request->all();

        $validator = Validator::make($data,[
            "email" => ['required', 'string', 'email', 'max:25']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::where('email','=',$data['email'])->first();

        if(! $user)
        {
            return response()->json("User not found.", 422);
        }

        $code = rand(10000,99999);

        $email = $user['email'];

        PasswordReset::create([   #CONTINUE HERE
            'email' => $email,
            'token' => $code
        ]);

        MailController::send_password_reset_code($code,$email);

        return response()->json('Check your email for reset code.');

    }

    public function check_password_reset_code(Request $request)
    {
        /*$code, $user_id, $new_password*/

        $data =$request->all();

        $validator = Validator::make($data,[
            "code" => "required|numeric|digits:5",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


    }



    public function resend_code(Request $req)
    {

        $data = $req->all();

        $validator = Validator::make($data,[
            "email" => "string|required|max:25|email",
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $user = User::where('email', '=',$data['email'])->first();

        if(!$user)
        {
            return response()->json("User not found");
        }

        if($user['verified'] == 1)
        {
            return response()->json("User is already verified.");
        }

        $code = EmailVerification::where('user_id', '=', $user['id'])->first();

        if(!$code)
        {
            $send_me_as_code = rand(10000,99999);

            EmailVerification::create([
                'user_id' => $user->id,
                'code' => $send_me_as_code,
            ]);
        }
        else
        {
            $send_me_as_code = $code['code'];
        }

        MailController::sendcode($send_me_as_code,$user->email);
        return response()->json("Success", 200);
    }

    public function create_card(Request $req)
    {

        if(Auth::check())
        {

            $validator = Validator::make($req->all(), [
                'email' => 'required|string|email|max:25',
                'address' => 'nullable|string|max:255',
                'phone_number1' => 'required|string|max:255',
                'phone_number2' => 'nullable|string|max:255',
                'link1' => 'nullable|string|max:255',
                'link2' => 'nullable|string|max:255',
                'link3' => 'nullable|string|max:255',
                'link4' => 'nullable|string|max:255',
                'link5' => 'nullable|string|max:255',
             ]);

             if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            if(! EmailVerificationController::check($req->email)) #allow different emails
            {
                return response()->json('go verify bitch', 300); #problem wrong login info
                #not verified
            };


            $user = Auth::user();
            //dd($user);
            if(! $user['email'] == $req['email'])
            {
                return respone()->json("smt wrong tf", 69);
            }

            $id = $user['id'];

            //$check = Card::where('user_id', '=', $id)->first();

            if(Card::where('user_id', '=', $id)->count() > 0)
            {
                return response()->json('u already have a card gtfo', 300);
            }

            $card = Card::create([
                    'address' => $req['address'],
                    'qr_code' => $req['qr_code'],
                    'user_id' => $id,

                ]);
            $id = $card['id'];
            $ph1 = $req['phone_number1'];
            $ph2 = $req['phone_number2'];

            $links = [ $req['link1'],$req['link2'],$req['link3'],$req['link4'],$req['link5'] ];
            if($ph2){
                PhoneNumberController::add($id, $ph1, $ph2);
            }
            else{
                PhoneNumberController::add($id, $ph1);
            }

            foreach($links as $link){
                if($link){
                    LinkController::add($id, $link);
                }
            }
            return response()->json('card created', 200);

        }
        else
        {
            print_r("u fucked up and now imma die :(");
            die;
        }
    }
}
