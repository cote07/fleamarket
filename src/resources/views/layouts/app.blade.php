<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    @yield('css')
</head>

<body class="body">
    <header class="header">
        <div class="header-inner">
            <div>
                <h1><a class="header-logo" href="/"><img src="{{ asset('img/logo.svg') }}" alt="logo"></a></h1>
            </div>
            <div class="header-content">
                <div class="search-content">
                    <form id="search-form" method="GET" action="{{ route('index') }}">
                        <input type="hidden" name="tab" value="{{ $activeTab }}">
                        <div>
                            <span class="material-icons-outlined search-icon" id="search-icon">
                                search
                            </span>
                            <input type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ request('keyword') }}" class="keyword" id="keyword-input">
                        </div>
                    </form>
                </div>
                <div class="hamburger-menu">
                    <div class="hamburger" onclick="toggleMenu()">
                        <span class="material-icons-outlined" id="menu-icon">
                            menu
                        </span>
                        <span class=" material-icons-outlined" id="close-icon">
                            close
                        </span>
                    </div>
                </div>
                <nav class="menu" id="menu">
                    <ul class="menu-list-content">
                        @if (Auth::check())
                        <li class="menu-list">
                            <form class="logout" action="/logout" method="post">
                                @csrf
                                <button class="logout-link">ログアウト</button>
                            </form>
                        </li>
                        @else
                        <li class="menu-list"><a href="/login" class="menu-link">ログイン</a></li>
                        @endif
                        <li class="menu-list"><a href="/mypage" class="menu-link">マイページ</a></li>
                        <li class="menu-list"><a href="/sell" class="menu-link link-sell">出品</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="main">
        @yield('content')
    </main>

    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>