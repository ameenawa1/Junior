<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Card;

class CardController extends Controller
{
    public function update(Request $request)
    {
        $user = auth()->user();
        $card = $user?->card;

        if ($request->hasFile('profile_image')) {
            if (!file_exists(public_path('uploaded_images'))) {
                mkdir(public_path('uploaded_images'), 0777, true);
            }
            $file = $request->file('profile_image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move('uploaded_images', $fileName);
        }
        $x = $request->all();
        $x['user_id'] = auth()->user()->id;
        $card = Card::create($x);
        
        $card->profile_image = isset($fileName) ? url('/') . '/uploaded_images/' . $fileName : null;
        $card->save();
        return response()->json([
            'message' => 'The card has been saved successfully',
            'card' => $card
        ], 200);
    }

    public function getCard(int $id){
        $card = Card::findOrFail($id);
        return response()->json(['card'=>$card]);
    }
    public function onlyCard(){ //only card
        $userId = auth()?->user()?->id;
        if($userId == null){
        return response()->json(['card'=>null]);

        }
        $card = Card::where('user_id','=',$userId)->get()->last();
        return response()->json(['card'=>$card]);
    }

}
