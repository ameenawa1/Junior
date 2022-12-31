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
        $contacts = User::with('contacts', 'contacts.card')->find(auth()->id());
        return response()->json([
            'message' => 'success',
            'data' => $contacts
        ]);
    }

    public function add_contact($contact_id)
    {
        $user = User::find($contact_id);
        if ($user == null)
            return response()->json('there is no user with this id', 400);

        if (auth()->id() == $contact_id)
            return response()->json('u can\'t add ur self');

        $contact = Contact::where(['contact_id' => $contact_id, 'user_id' => auth()->id()])->first();
        if ($contact != null)
            return response()->json('u already have this user in ur contacts list');

        Contact::create([
            'user_id' => auth()->id(),
            'contact_id' => $contact_id
        ]);
        $contacts = User::with('contacts', 'contacts.card')->find(auth()->id());

        return response()->json([
            'message' => 'added successfully',
            'data' => $contacts
        ]);
    }

    public function distroy($contact_id){
        $contact = Contact::where('contact_id', $contact_id)->delete();
        return response()->json('contact has deleted successfully');
    }
}
