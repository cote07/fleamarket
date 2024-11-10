@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell-content">
    <div class="form__heading">
        <h2 class="form__title">商品の出品</h2>
    </div>
    <form action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data" class="form">
        @csrf
        <div class="form-group">
            <div class="flex">
                <p class="form-group-subtitle">商品画像</p>
                <div class="item-image-content">
                    <label class="item-image" for="item_picture-input">画像を選択する</label>
                    <input type="file" name="item_picture" accept="image/*" id="item_picture-input">
                    <p id="picture-select">選択しました</p>
                </div>
            </div>
            <div class="form__error">
                @error('item_picture')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div>
            <h3 class="form-group-title">商品の詳細</h3>
        </div>
        <div class="form-group form-group-category">
            <div class="flex">
                <p class="form-group-subtitle">カテゴリー</p>
                @foreach($categories as $category)
                <input type="checkbox" name="content[]" value="{{ $category->id }}" id="category-{{ $category->id }}" class="category-checkbox">
                <label for="category-{{ $category->id }}" class="category-label">{{ $category->content }}</label>
                @endforeach
            </div>
            <div class="form__error">
                @error('content')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="flex">
                <p class="form-group-subtitle">商品の状態</p>
                <select name="condition" class="form-select">
                    <option value="" selected disabled>選択してください</option>
                    <option value="良好">良好</option>
                    <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                    <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                    <option value="状態が悪い">状態が悪い</option>
                </select>
            </div>
            <div class="form__error">
                @error('condition')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div>
            <h3 class="form-group-title">商品名と説明</h3>
        </div>
        <div class="form-group">
            <div class="flex">
                <p class="form-group-subtitle">商品名</p>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
            </div>
            <div class="form__error">
                @error('name')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="flex">
                <p class="form-group-subtitle">商品の説明</p>
                <textarea name="description" class="form-textarea" rows="6">{{ old('description') }}</textarea>
            </div>
            <div class="form__error">
                @error('description')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="flex">
                <p class="form-group-subtitle">販売価格</p>
                <input type="text" name="price" class="form-control" value="{{ old('price') }}" placeholder="¥">
            </div>
            <div class="form__error">
                @error('price')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="sell-btn">
            <button type="submit" class="form__button-submit">出品する</button>
        </div>
    </form>
</div>
@endsection