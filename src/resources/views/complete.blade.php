<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/complete.css') }}">
</head>

<body>
    <main>
        <div class="content">
            <p>購入が完了しました</p>
            <p>商品名: {{ $item->name }}</p>
            <p>価格: ¥{{ $item->price }}</p>
            <a class="link" href="{{ route('index') }}">トップページに戻る</a>
        </div>
    </main>
</body>

</html>