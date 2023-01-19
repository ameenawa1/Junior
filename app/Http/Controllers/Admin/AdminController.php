<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AdminController extends Controller
{
    public function __construct()
    {
        /*$this->middleware('auth:web', ['except' => ['login']]);*/

    }

    public function logout(){
        if(Auth::check()){
            Auth::logout();
        }
    }

    public function login(Request $req)
    {

        //return view('auth.login');

        if(! Auth::check()){

            $data = $req->all();

            //dd($data);
            $validator = Validator::make($data,[
                'email' => 'required|email|string|max:25',
                'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator->errors());
            }
            if (!$token = auth()->attempt($validator->validated())) {
                return back()->withErrors(['email' =>'Unauthorized']);
            }

            $user = ['user' => Auth::user()];


            if($user['user']['role_id'] == null){
                return back()->withErrors(['email' =>'Unauthorized']);
            }

            if($user['user']['role_id'] != 1 ){
                return back()->withErrors(['email' =>'Unauthorized']);
            }
            //$token = Auth::attempt(/*$req->except('_token')*/);
            //Auth::login($user);
            $data = [
                'token' => $token,
                'user' => Auth::user(),
            ];

            $token='Bearer '.$token;



            //$response->header('Authorization',$token);
            Cookie::queue('Authorization', $token);

            return redirect('/dashboard');
        }
        else{
            dd('x');
            return back()->withErrors(['email' =>'Already logged in']);
        }


    }
}
