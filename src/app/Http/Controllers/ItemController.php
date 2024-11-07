<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Favorite;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $activeTab = $request->input('tab', 'recommended');
        $keyword = $request->input('keyword', '');

        if (!in_array($activeTab, ['recommended', 'mylist'])) {
            $activeTab = 'recommended';
        }

        $recommendedItems = Item::where('user_id', '!=', optional($user)->id)
            ->when($keyword, function ($query) use ($keyword) {
                return $query->where('name', 'like', '%' . $keyword . '%');
            })
            ->get();

        $favorites = auth()->check() ? auth()->user()->favorites()->with('item')->get() : collect();
        $purchasedItems = $user ? $user->purchases()->pluck('item_id')->toArray() : [];

        if ($activeTab === 'mylist' && $keyword) {
            $favorites = $favorites->filter(function ($favorite) use ($keyword) {
                return stripos($favorite->item->name, $keyword) !== false;
            });
        }

        return view('index', compact('recommendedItems', 'favorites', 'activeTab', 'keyword', 'purchasedItems'));
    }





    public function item($item_id)
    {
        $item = Item::with(['comments.user.profile', 'categories'])->findOrFail($item_id);
        $activeTab = request('tab', 'recommended');
        $comments = $item->comments;

        return view('item', compact('item', 'activeTab','comments'));
    }

    public function sell()
    {
        $categories = Category::all();
        $activeTab = request('tab', 'recommended');

        return view('sell', compact('categories', 'activeTab'));
    }

    public function store(ExhibitionRequest $request)
    {
        $item = new Item();
        $item->user_id = Auth::id();
        $item->name = $request->input('name');
        $item->description = $request->input('description');
        $item->price = $request->input('price');
        $item->condition = $request->input('condition');

        if ($request->hasFile('item_picture')) {
            $filePath = $request->file('item_picture')->store('images', 'public');
            $item->item_picture = $filePath;
        }
        $item->save();

        if ($request->has('content')) {
            $item->categories()->attach($request->input('content'));
        }

        return redirect()->route('index');
    }
}
