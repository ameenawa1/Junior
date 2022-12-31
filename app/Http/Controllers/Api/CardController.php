<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function update(Request $request)
    {
        $user = auth()->user();
        $card = $user->card;
        $card->fill($request->all());
        $card->save();
        return response()->json([
            'message' => 'The card has been saved successfully',
            'card' => $card
        ], 200);
    }
}
