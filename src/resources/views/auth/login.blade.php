<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
</head>

<body class="body">
    <header class="header">
        <div class="header-inner">
            <div>
                <h1><a class="header-logo" href="/"><img src="{{ asset('img/logo.svg') }}" alt="logo"></a></h1>
            </div>
        </div>
    </header>
    <main class="main">
        <div class="login__content">
            <div class="login-form__heading">
                <h2 class="login-form__title">ログイン</h2>
            </div>
            <form class="form" action="/login" method="post">
                @csrf
                <div class="form__group">
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <label class="form__label">ユーザー名/メールアドレス</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form__input">
                        </div>
                        <div class="form__error">
                            @error('email')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form__group">
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <label class="form__label">パスワード</label>
                            <input type="password" name="password" class="form__input">
                        </div>
                        <div class="form__error">
                            @error('password')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form__button">
                    <button class="form__button-submit" type="submit">ログインする</button>
                </div>
            </form>
            <div class="register__link">
                <a class="register__button-submit" href="/register">会員登録はこちら</a>
            </div>
        </div>
    </main>
</body>

</html>