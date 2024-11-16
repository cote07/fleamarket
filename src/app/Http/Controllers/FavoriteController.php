<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function create($item_id)
    {
        $user = Auth::user();
        $exists = Favorite::where('user_id', $user->id)
            ->where('item_id', $item_id)
            ->exists();

        if (!$exists) {
            Favorite::create([
                'user_id' => $user->id,
                'item_id' => $item_id,
            ]);
        }
        return back();
    }

    public function delete($item_id)
    {
        $user = Auth::user();

        Favorite::where('user_id', $user->id)
            ->where('item_id', $item_id)
            ->delete();
        return back();
    }
}
