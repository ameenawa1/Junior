<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;


class ContactController extends Controller
{
    public function contacts_list()
    {
        $contacts = User::with('contacts')->find(auth()->id());
        $user = User::find(auth()->id());
        $user = auth()->user();
        $tez=$user->contacts()->get();
        //dd($tez); 
        return response()->json([
            'message' => 'success',
            'data' => $tez
        ], 200);
    }

    public function add_contact($user_id)
    {
        $user = User::find($user_id);
        if ($user == null)
            return response()->json(['error' => 'there is no user with this id'], 400);

        if (auth()->id() == $user_id)
            return response()->json(["error" => 'You can\'t add yourself'], 300);

        $contact = Contact::where(['contact_id' => $user_id, 'user_id' => auth()->id()])->first();
        if ($contact != null)
            return response()->json(['error' => 'You already have this user in your contacts list'], 300);

        Contact::create([
            'user_id' => auth()->id(),
            'contact_id' => $user_id
        ]);
        $contacts = User::with('contacts', 'contacts.card')->find(auth()->id());

        return response()->json([
            'message' => 'added successfully',
            'data' => $contacts
        ], 200);
    }

    public function destroy($contact_id)
    {
        $contact = Contact::where(['contact_id' => $contact_id, 'user_id' => auth()->id()])->delete();
        return response()->json([
            'message' => 'contact has deleted successfully',
        ], 200);
    }
}