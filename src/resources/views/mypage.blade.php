@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="flex">
    <div class="flex-image">
        <img id="profile-image" src="{{ $profile->profile_picture ? asset('storage/' . $profile->profile_picture) : asset('img/sample.jpg') }}" alt="profile image">
        <h2 class="name">{{ $user->name }}</h2>
    </div>
    <a href="{{ route('profile') }}" class="profile-link">プロフィールを編集</a>
</div>

<div class="tabs">
    <a href="/mypage?tab=sell" class="tab-link {{ $activeTab === 'sell' ? 'active' : '' }}">出品した商品</a>
    <a href="/mypage?tab=buy" class="tab-link {{ $activeTab === 'buy' ? 'active' : '' }}">購入した商品</a>
</div>


@if ($activeTab === 'sell')
<div class="item-content">
    @foreach($recommendedItems as $item)
    <div class="item-list">
        <a href="{{ route('item', ['item_id' => $item->id]) }}">
            @if (Str::startsWith($item->item_picture, 'http'))
            <img src="{{ $item->item_picture }}" alt="item Image" class="item-list-img">
            @else
            <img src="{{ asset('storage/' . $item->item_picture) }}" alt="item Image" class="item-list-img">
            @endif
        </a>
        <p>{{ $item->name }}</p>
    </div>
    @endforeach
</div>
@endif

@if ($activeTab === 'buy')
<div class=" item-content">
    @foreach($purchases as $purchase)
    <div class="item-list">
        <a href="{{ route('item', ['item_id' => $purchase->item->id]) }}">
            @if (Str::startsWith($purchase->item->item_picture, 'http'))
            <img src="{{ $purchase->item->item_picture }}" alt="item Image" class="item-list-img">
            @else
            <img src="{{ asset('storage/' . $purchase->item->item_picture) }}" alt="item Image" class="item-list-img">
            @endif
        </a>
        <p>{{ $purchase->item->name }}</p>
    </div>
    @endforeach
</div>
@endif
@endsection