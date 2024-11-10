@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<form action="{{ route('purchase.store', ['item_id' => $item_id]) }}" method="POST" class="form">
    @csrf
    <div class="form-content">
        <div class="flex form-group">
            <div class="item-img">
                @if (Str::startsWith($item->item_picture, 'http'))
                <img src="{{ $item->item_picture }}" alt="item Image" class="item-list-img">
                @else
                <img src="{{ asset('storage/' . $item->item_picture) }}" alt="item Image">
                @endif
            </div>
            <div>
                <h2 class="item-name">{{ $item->name }}</h2>
                <p class="item-price">¥{{ $item->price }}</p>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">支払い方法</label>
            <select name="payment" id="paymentSelect">
                <option value="" selected disabled>選択してください</option>
                <option value="コンビニ支払い" {{ old('payment') == 'コンビニ支払い' ? 'selected' : '' }}>コンビニ支払い</option>
                <option value="カード支払い" {{ old('payment') == 'カード支払い' ? 'selected' : '' }}>カード支払い</option>
            </select>
            <div class="form__error">
                @error('payment')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="address-flex">
                <label class="form-label">配送先</label>
                <a href="{{ route('address', ['item_id' => $item_id]) }}" class="address-change">変更する</a>
            </div>
            @php
            $temporary_address = session('temporary_address_item' . $item_id, []);
            @endphp
            <input type="hidden" name="postal_code" value="{{ old('postal_code', $temporary_address['postal_code'] ?? $profile->postal_code) }}">
            <input type="hidden" name="address" value="{{ old('address', $temporary_address['address'] ?? $profile->address) }}">
            <input type="hidden" name="building" value="{{ old('building', $temporary_address['building'] ?? $profile->building) }}">
            <p class="address-text">〒{{ $temporary_address['postal_code'] ?? $profile->postal_code }}</p>
            <p class="address-text">{{ $temporary_address['address'] ?? $profile->address }}{{ $temporary_address['building'] ?? $profile->building }}</p>
            <div class="form__error">
                @error('shipping_address')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
    <div class="content">
        <table class="detail-table">
            <tr class="detail-row">
                <td class="detail-label">商品代金</td>
                <td class="detail-value"><span class="span">¥</span>{{ $item->price }}</td>
            </tr>
            <tr class="detail-row">
                <td class="detail-label">支払い方法</td>
                <td class="detail-value">
                    <span class="payment-method"></span>
                </td>
            </tr>
        </table>
        <button id="custom-stripe-button" class="custom-button">購入する</button>
    </div>
</form>
@endsection