<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;


class ContactController extends Controller
{
    public function contacts_list()
    {
        if(Auth::check())
        {

            /*$contacts = User::with('contacts', 'contacts.card')->find(auth()->id());*/

            $contacts = User::mycontactshelper(Auth::User());

            //dd($contacts);
            return response()->json([
                'message' => 'success',
                'data' => $contacts,
            ], 200);
        }else{
            return response()->json(["error" => "Please login to continue"], 401);
        }
    }

    public function add_contact($user_id=null)
    {
        if(Auth::check())
        {
            if($user_id == null)
            {
                return response()->json(['error' => 'id is required'], 400);
            }
            $validator = Validator::make(['id' => $user_id], [
                'id' => 'required|integer',
                ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $user = User::find($contact_id);
            //dd($user);
            if ($user == null)
                return response()->json(['error' => 'there is no user with this id'], 400);

            if (auth()->id() == $contact_id)
                return response()->json(["error" => 'You can\'t add yourself'], 300);

            $contact = Contact::where(['contact_id' => $contact_id, 'user_id' => auth()->id()])->first();
            if ($contact != null)
                return response()->json(['error' => 'You already have this user in your contacts list'], 300);

            /*Contact::create([
                'user_id' => auth()->id(),
                'contact_id' => $user_id
            ]);*/

            $contact = auth()->user()->contacts()->attach($user_id);
            //$contacts = User::with('contacts', 'contacts.card')->find(auth()->id());

            return response()->json([
                'message' => 'added successfully',
                /*'data' => $contacts*/
            ], 200);
        }
        else{
            return response()->json(["error" => "Please login to continue"], 401);
        }
    }

    public function destroy($contact_id)
    {
        $contact = Contact::where(['contact_id' => $contact_id, 'user_id' => auth()->id()])->delete();
        return response()->json([
            'message' => 'contact has deleted successfully',
        ], 200);
    }
}
