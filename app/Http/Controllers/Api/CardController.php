<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Card;
use Auth;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{
    public function update(Request $request)
    {
        if(Auth::check()){

            /*$validator = Validator::make($req->all(), [
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
            }*/



            $user = auth()->user();
            $card = $user->card;
            dd($card);
            if ($request->hasFile('profile_image')) {
                if (!file_exists(public_path('uploaded_images'))) {
                    mkdir(public_path('uploaded_images'), 0777, true);
                }
                $file = $request->file('profile_image');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move('uploaded_images', $fileName);
            }
            $card->fill($request->all());
            $card->profile_image = isset($fileName) ? url('/') . '/uploaded_images/' . $fileName : null;
            $card->save();
            return response()->json([
                'message' => 'The card has been saved successfully',
                'card' => $card
            ], 200);
        }
        else
        {
            return response()->json([
                "message" => "log in to continue."
            ],301);
        }
    }

    public function getCard(int $id){
        $card = Card::findOrFail($id);
        return response()->json(['card'=>$card]);
    }
}
