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
        <h2>{{ $item->name }}</h2>
        <p>ブランド名</p>
        <p>¥{{ $item->price }}(税込)</p>
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
            <span class="material-icons-outlined">
                chat_bubble
            </span>
            <p class="comment-count">{{ $item->comments->count() }}</p>
        </div>
        <a href="{{ route('purchase', ['item_id' => $item->id]) }}">購入手続きへ</a>
        <div class="description">
            <h3>商品説明</h3>
            <p>{{ $item->description }}</p>
        </div>
        <div class="information">
            <h3>商品の情報</h3>
            <div>
                <p>カテゴリー</p>
                    <ul>
                        @foreach($item->categories as $category)
                        <li>{{ $category->content }}</li>
                        @endforeach
                    </ul>
                <p>商品の状態<span>{{ $item->condition }}</span></p>
            </div>
        </div>
        <div class="comment">
            <h3>コメント({{ $item->comments->count() }})</h3>
            @foreach($comments as $comment)
            <div class="comment-box">
                <img src="{{ asset('storage/' . $comment->user->profile->profile_picture) }}" alt="profile image">
                <p>{{ $comment->user->name }}</p>
                <p>{{ $comment->content }}</p>
            </div>
            @endforeach
            <form action="{{ route('comments.store', $item->id) }}" method="POST">
                @csrf
                <p>商品へのコメント</p>
                <textarea name="content" rows="6"></textarea>
                <div class="form__error">
                    @error('content')
                    {{ $message }}
                    @enderror
                </div>
                <button type="submit">コメントを送信する</button>
            </form>
        </div>
    </div>
</div>
@endsection