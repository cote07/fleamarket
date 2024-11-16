<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile;
        $activeTab = request('tab', 'recommended');

        return view('profile', compact('user', 'profile', 'activeTab'));
    }

    public function update(ProfileRequest $profileRequest, AddressRequest $addressRequest)
    {
        $user = Auth::user();
        $user->name = $addressRequest->input('name', $user->name);
        $user->save();

        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
        $profile->postal_code = $addressRequest->input('postal_code', $profile->postal_code);
        $profile->address = $addressRequest->input('address', $profile->address);
        $profile->building = $addressRequest->input('building', $profile->building);

        if ($profileRequest->hasFile('profile_picture')) {
            $path = $profileRequest->file('profile_picture')->store('profile_pictures', 'public');
            $profile->profile_picture = $path;
        }
        $profile->save();

        return redirect()->route('index');
    }

    public function mypage()
    {
        $userId = Auth::id();
        $user = User::findOrFail($userId);
        $recommendedItems = Item::where('user_id', $userId)->get();
        $purchases = Purchase::all();
        $activeTab = request('tab', 'sell');
        $profile = Profile::where('user_id', $userId)->first();

        return view('mypage', compact('user', 'recommendedItems', 'purchases','activeTab', 'profile'));
    }
}
