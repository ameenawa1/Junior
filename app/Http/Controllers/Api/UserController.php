<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Card;
use App\Http\Controllers\Api\PhoneNumberController;
use App\Http\Controllers\Api\LinkController;
use Illuminate\Support\Facades\Validator;
use Auth;

class UserController extends Controller
{

    public function get_user_by_id($id=null)
    {
        return $id ? User::find($id) : User::all();
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
