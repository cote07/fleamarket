<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
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
                <h2>ログイン</h2>
            </div>
            <form class="form" action="/login" method="post">
                @csrf
                <div class="form__group">
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="メールアドレス">
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
                            <input type="password" name="password" placeholder="パスワード">
                        </div>
                        <div class="form__error">
                            @error('password')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form__button">
                    <button class="form__button-submit" type="submit">ログイン</button>
                </div>
            </form>
            <div class="login__link">
                <a class="login__button-submit" href="/register">会員登録</a>
            </div>
        </div>
    </main>
</body>

</html>