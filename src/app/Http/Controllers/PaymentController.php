<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Profile;
use App\Models\Item;
use App\Models\Purchase;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function purchase($item_id)
    {
        $item = Item::findOrFail($item_id);
        $profile = Profile::where('user_id', Auth::id())->first();
        $activeTab = request('tab', 'recommended');

        return view('purchase', compact('item_id', 'item', 'profile', 'activeTab'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $paymentMethod = $request->payment;
        session(['payment_method' => $paymentMethod]);

        if ($paymentMethod === 'カード支払い') {
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success', ['item_id' => $item_id]),
            'cancel_url' => route('purchase', ['item_id' => $item_id]),
        ]);
    }

        if ($paymentMethod === 'コンビニ支払い') {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['konbini'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->name,
                        ],
                        'unit_amount' => $item->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('purchase.success', ['item_id' => $item_id]),
                'cancel_url' => route('purchase', ['item_id' => $item_id]),
            ]);
        }

        return redirect($session->url, 303);
     }

    public function address($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $profile = $user->profile;
        $activeTab = request('tab', 'recommended');

        return view('address', compact('user', 'item_id', 'item', 'profile', 'activeTab'));
    }

    public function update(AddressRequest $request, $item_id)
    {
        $user = Auth::user();
        $temporary_address = [
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building ?? null,
        ];

        session(['temporary_address_item' . $item_id => $temporary_address]);

        return redirect()->route('purchase', ['item_id' => $item_id]);
    }

    public function success(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $profile = $user->profile;
        $paymentMethod = session('payment_method');

        $purchase = new Purchase();
        $purchase->user_id = $user->id;
        $purchase->item_id = $item->id;
        $purchase->payment = $paymentMethod;
        $temporary_address = session('temporary_address_item' . $item_id);

        if ($temporary_address) {
            $purchase->postal_code = $temporary_address['postal_code'];
            $purchase->address = $temporary_address['address'];
            $purchase->building = $temporary_address['building'];
            session()->forget('temporary_address_item' . $item_id);
        } else {
            $purchase->postal_code = $profile->postal_code;
            $purchase->address = $profile->address;
            $purchase->building = $profile->building;
        }

        $purchase->save();

        return view('complete', ['item' => $item]);
    }

}
