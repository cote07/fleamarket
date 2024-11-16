@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="tabs">
    <a href="/?tab=recommended&keyword={{ request('keyword', '') }}" class="tab-link {{ $activeTab === 'recommended' ? 'active' : '' }}">おすすめ</a>
    <a href="/?tab=mylist&keyword={{ request('keyword', '') }}" class="tab-link {{ $activeTab === 'mylist' ? 'active' : '' }}">マイリスト</a>
</div>
@if ($activeTab === 'recommended')
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
        @if (in_array($item->id, $allPurchasedItems))
        <div class="sold-content">
            <span class="sold-label">Sold</span>
        </div>
        @endif
        <p>{{ $item->name }}</p>
    </div>
    @endforeach
</div>
@endif
@if ($activeTab === 'mylist')
@if (Auth::check())
<div class=" item-content">
    @foreach($favorites as $favorite)
    <div class="item-list">
        <a href="{{ route('item', ['item_id' => $favorite->item->id]) }}">
            @if (Str::startsWith($favorite->item->item_picture, 'http'))
            <img src="{{ $favorite->item->item_picture }}" alt="item Image" class="item-list-img">
            @else
            <img src="{{ asset('storage/' . $favorite->item->item_picture) }}" alt="item Image" class="item-list-img">
            @endif
        </a>
        @if (in_array($favorite->item->id, $allPurchasedItems))
        <div class="sold-content">
            <span class="sold-label">Sold</span>
        </div>
        @endif
        <p>{{ $favorite->item->name }}</p>
    </div>
    @endforeach
</div>
@endif
@endif
@endsection