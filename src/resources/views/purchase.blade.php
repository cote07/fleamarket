@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="item-img">
    @if (Str::startsWith($item->item_picture, 'http'))
    <img src="{{ $item->item_picture }}" alt="item Image" class="item-list-img">
    @else
    <img src="{{ asset('storage/' . $item->item_picture) }}" alt="item Image">
    @endif
</div>
<h2>{{ $item->name }}</h2>
<p>¥{{ $item->price }}</p>

<form action="{{ route('purchase.store', ['item_id' => $item_id]) }}" method="POST">
    @csrf
    <label>支払い方法</label>
    <select name="payment" id="paymentSelect">
        <option value="" selected disabled>選択してください</option>
        <option value="コンビニ支払い">コンビニ支払い</option>
        <option value="カード支払い">カード支払い</option>
    </select>
    <label>配送先</label>
    <a href="{{ route('address', ['item_id' => $item_id]) }}">変更する</a>
    @php
    $temporary_address = session('temporary_address_item' . $item_id, []);
    @endphp
    <input type="hidden" name="postal_code" value="{{ old('postal_code', $temporary_address['postal_code'] ?? $profile->postal_code) }}">
    <input type="hidden" name="address" value="{{ old('address', $temporary_address['address'] ?? $profile->address) }}">
    <input type="hidden" name="building" value="{{ old('building', $temporary_address['building'] ?? $profile->building) }}">

    <p>〒{{ $temporary_address['postal_code'] ?? $profile->postal_code }}</p>
    <p>{{ $temporary_address['address'] ?? $profile->address }}{{ $temporary_address['building'] ?? $profile->building }}</p>

    <div>
        <div>
            <p>商品代金</p>
            <p>¥{{ $item->price }}</p>
        </div>
        <div>
            <p>支払い方法</p>
            <p class="payment-method"></p>
        </div>
        <button id="checkout-button">購入する</button>
    </div>
    @endsection