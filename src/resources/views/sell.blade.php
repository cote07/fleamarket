@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell-content">
    <h2>商品の出品</h2>
    <form action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data" class="form">
        @csrf
        <div class="form-group">
            <div class="flex">
                <label class="form-label">商品画像</label>
                <input type="file" name="item_picture" accept="image/*">
            </div>
            <div class="form__error">
                @error('item_picture')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="flex">
                <label class="form-label">カテゴリー</label>
                @foreach($categories as $category)
                <label>
                    <input type="checkbox" name="content[]" value="{{ $category->id }}">
                    {{ $category->content }}
                </label>
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
                <label class="form-label">商品の状態</label>
                <select name="condition">
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
        <div class="form-group">
            <div class="flex">
                <label class="form-label">商品名</label>
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
                <label class="form-label">説明</label>
                <textarea name="description" class="form-control" rows="6">{{ old('description') }}</textarea>
            </div>
            <div class="form__error">
                @error('description')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="flex">
                <label class="form-label">販売価格</label>
                <input type="text" name="price" class="form-control" value="{{ old('price') }}" placeholder="¥">
            </div>
            <div class="form__error">
                @error('price')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="sell-btn">
            <button type="submit" class="sell-button">出品する</button>
        </div>
    </form>
</div>
@endsection