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
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function get_user_by_id($id = null)
    {
        return $id ? User::find($id) : User::all();
    }

    public function resetPass(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            "email" => ['required', 'string', 'email', 'max:25']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::where('email', '=', $data['email'])->first();
        if (!$user) {
            return response()->json("User not found.", 422);
        }
        $code = rand(10000, 99999);
        $email = $user['email'];
        PasswordReset::where('email', $user['email'])->delete();
        PasswordReset::create([   #CONTINUE HERE ----- ADD SOME REFERENCE TO THE USER FOR ADDED SECURITY THEN-
            # CONTINUE IN CHECK_PASSWORD_RESET_CODE FUNCTION AND CHECK IF THE CODE IS
            # CORRECT AND OWNED BY THE USER OR NOT
            # THEN CREATE A PASSWORD CHANGE FUNCTION THAT UPDATES THE DB
            'email' => $email,
            'token' => $code
        ]);
        $x = new MailController();
        $x->send_password_reset_code($code, $email);
        return response()->json('Check your email for the password reset code.');
    }

    public function changePassword(Request $req)
    {
        $data = $req->all();
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'code' => 'required|numeric',
            'new_password' => 'required|string|confirmed|min:8',
            'new_password_confirmation' => 'required|string|min:8|same:new_password',
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $user = User::where('email', $data['email'])->first();
        if (!$user) return response()->json("User not found");

        $password_reset = PasswordReset::where('email', $data['email'])->first();
        if (!$password_reset) return response()->json("There is no reset code");
        if($password_reset->token != $data['code']) return response()->json("The reset code is incorrect");

        $user->password = Hash::make($data['new_password']);
        $user->save();
        PasswordReset::where('email', $user->email)->delete();
        return response()->json("Password has been changed successfully");
    }

    public function resend_code(Request $req)
    {
        $data = $req->all();
        $validator = Validator::make($data, [
            "email" => "string|required|max:25|email",
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::where('email', '=', $data['email'])->first();
        if (!$user) {
            return response()->json("User not found");
        }
        if ($user['verified'] == 1) {
            return response()->json("User is already verified.");
        }
        $code = EmailVerification::where('user_id', '=', $user['id'])->first();
        if (!$code) {
            $send_me_as_code = rand(10000, 99999);
            EmailVerification::create([
                'user_id' => $user->id,
                'code' => $send_me_as_code,
            ]);
        } else {
            $send_me_as_code = $code['code'];
        }
        $x = new MailController();
        $x->sendcode($send_me_as_code, $user->email);
        return response()->json("Success", 200);
    }

    public function create_card(Request $req)
    {
        if (Auth::check()) {
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
            #validate email regex DONE
            #check if email exists in db
            #if it exists check if the owner is the same person creating the card
            #else reject the email
            $email = $req->email;
            $email_checker = User::where('email', '=', $email)->first();
            $user = Auth::user();
            if ($email_checker) {
                #email is in db
                #check owner
                #$current_user = Auth::user();
                if (!$user->email == $email) {
                    return response()->json("This email is owned by a different account.", 301);
                }
            }
            /*if(! EmailVerificationController::check($req->email)) #allow different emails
            {
                if (! $token = auth()->attempt($validator->validated())) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
                $token = Auth::attempt($request->except('_token'));
                #dd($token);
                return response()->json("Email not found",404);
                #return response()->json('test message', 300); #problem wrong login info
                #not verified
            };*/
            //dd($user);
            if (!$user['email'] == $req['email']) {
                return respone()->json("smt wrong tf", 69);
            }
            $id = $user['id'];
            //$check = Card::where('user_id', '=', $id)->first();
            if (Card::where('user_id', '=', $id)->count() > 0) {
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
            $links = [$req['link1'], $req['link2'], $req['link3'], $req['link4'], $req['link5']];
            if ($ph2) {
                PhoneNumberController::add($id, $ph1, $ph2);
            } else {
                PhoneNumberController::add($id, $ph1);
            }
            foreach ($links as $link) {
                if ($link) {
                    LinkController::add($id, $link);
                }
            }
            return response()->json('card created', 200);
        } else {
            return response()->json("Please login to create a card", 401);
        }
    }
}
