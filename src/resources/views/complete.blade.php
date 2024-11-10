<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>
</head>

<body>
    <p>購入が完了しました</p>
    <p>商品名: {{ $item->name }}</p>
    <p>価格: ¥{{ $item->price }}</p>
    <p>お届け先: {{ $item->address }}</p>
    <a href="{{ route('index') }}">トップページに戻る</a>
</body>

</html>