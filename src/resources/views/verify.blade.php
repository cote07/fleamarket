<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/verify.css') }}">
    <title>coachtechフリマ</title>
</head>

<body>
    <div class="main-content">
        <h2>メールアドレスの確認</h2>
        <p>確認用のメールを {{ auth()->user()->email }} 宛に送信しました。<br>メールアドレスの確認を行ってください。</p>
        <form action="/resend-verification-email" method="POST">
            @csrf
            <button type="submit">確認メールを再送信</button>
        </form>
    </div>
</body>

</html>