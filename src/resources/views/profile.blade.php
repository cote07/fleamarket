@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-content">
    <div class="form__heading">
        <h2 class="form__title">プロフィール設定</h2>
    </div>
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="form">
        @csrf
        @method('PATCH')
        <div class="form-group">
            <div class="image-flex">
                <div class="picture">
                    <img id="profile-image" src="{{ $profile->profile_picture ? asset('storage/' . $profile->profile_picture) : asset('img/sample.jpg') }}" alt="profile image">
                </div>
                <label for="profile-picture-input" class="picture-label">画像を選択する</label>
                <input type="file" name="profile_picture" accept="image/*" id="profile-picture-input">
                <p id="picture-select">選択しました</p>
            </div>
            <div class="form__error">
                @error('profile_picture')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="flex">
                <label class="form-label">ユーザー名</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}">
            </div>
            <div class="form__error">
                @error('name')
                {{ $message }}
                @enderror
            </div>
        </div>
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
            <button type="submit" class="form__button-submit">更新する</button>
        </div>
    </form>
</div>
@endsection