@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address-content">
    <h2>住所の変更</h2>
    <form action="{{ route('address.update', ['item_id' => $item_id]) }}" method="POST" enctype="multipart/form-data" class="form">
        @csrf
        @method('PATCH')
        <div class="form-group">
            <div class="flex">
                <label class="form-label">郵便番号</label>
                <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $profile->postal_code ?? '') }}">
            </div>
            <div class="form__error">
                @error('postal_code')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="flex">
                <label class="form-label">住所</label>
                <input type="text" name="address" class="form-control" value="{{ old('address', $profile->address ?? '') }}">
            </div>
            <div class="form__error">
                @error('address')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="flex">
                <label class="form-label">建物名</label>
                <input type="text" name="building" class="form-control" value="{{ old('building', $profile->building ?? '') }}">
            </div>
            <div class="form__error">
                @error('building')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div>
            <button type="submit" class="button">更新する</button>
        </div>
    </form>
</div>
@endsection