@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="item-detail">
    <div class="item-img">
        @if (Str::startsWith($item->item_picture, 'http'))
        <img src="{{ $item->item_picture }}" alt="item Image" class="item-list-img">
        @else
        <img src="{{ asset('storage/' . $item->item_picture) }}" alt="item Image">
        @endif
    </div>
    <div class="item-text">
        <h2 class="item-name">{{ $item->name }}</h2>
        <p class="item-brand">ブランド名</p>
        <p class="item-price">¥{{ $item->price }}(税込)</p>
        <div class="flex">
            <div class="favorite-button">
                @if (auth()->check() && auth()->user()->favorites->contains('item_id', $item->id))
                <form action="{{ route('favorite.delete', ['item_id' => $item->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="favorite-btn" title="お気に入り削除">
                        <span class="material-icons-outlined star-icon active">
                            star
                        </span>
                    </button>
                </form>
                @else
                <form action="{{ route('favorite.create', ['item_id' => $item->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="favorite-btn" title="お気に入り追加">
                        <span class="material-icons-outlined star-icon">
                            star
                        </span>
                    </button>
                </form>
                @endif
                <p class="favorite-count">{{ $item->favorites->count() }}</p>
            </div>
            <div class="comment-button">
                <span class="material-icons-outlined chat-icon">
                    chat_bubble
                </span>
                <p class="comment-count">{{ $item->comments->count() }}</p>
            </div>
        </div>
        @if (in_array($item->id, $allPurchasedItems))
        <p class="purchase-disabled-message">この商品は売り切れです。</p>
        @else
        <a href="{{ route('purchase', ['item_id' => $item->id]) }}" class="purchase-link">購入手続きへ</a>
        @endif
        <div class="description">
            <h3 class="subtitle">商品説明</h3>
            <p class="description-text">{{ $item->description }}</p>
        </div>
        <div class="information">
            <h3 class="subtitle">商品の情報</h3>
            <div>
                <div class="information-flex">
                    <p class="information-text">カテゴリー</p>
                    <div>
                        @foreach($item->categories as $category)
                        <span class="span">{{ $category->content }}</span>
                        @endforeach
                    </div>
                </div>
                <p class="information-text">商品の状態<span class="span-condition">{{ $item->condition }}</span></p>
            </div>
        </div>
        <div class="comment">
            <h3 class="comment-title">コメント({{ $item->comments->count() }})</h3>
            @foreach($comments as $comment)
            <div class="comment-box">
                <div class="comment-flex">
                    <img src="{{ $comment->user->profile->profile_picture ? asset('storage/' . $comment->user->profile->profile_picture) : asset('img/sample.jpg') }}" alt="profile image" class="comment-img">
                    <p class="name">{{ $comment->user->name }}</p>
                </div>
                <p class="comment-text">{{ $comment->content }}</p>
            </div>
            @endforeach
            <form action="{{ route('comments.store', $item->id) }}" method="POST">
                @csrf
                <p class="information-text">商品へのコメント</p>
                <textarea name="content" class="comment-textarea">{{ old('content') }}</textarea>
                <div class="form__error">
                    @error('content')
                    {{ $message }}
                    @enderror
                </div>
                <button type="submit" class="form__button-submit">コメントを送信する</button>
            </form>
        </div>
    </div>
</div>
@endsection