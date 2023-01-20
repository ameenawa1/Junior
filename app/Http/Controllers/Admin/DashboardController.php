<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


class DashboardController extends Controller
{
    public function index(){

        $users = User::where('role_id', '!=', 1)->paginate(5);


        return view('home', compact('users'));
    }




}
