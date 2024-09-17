<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Default Title')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/index_style.css') }}">
    @stack('styles')
</head>
<body>
    <header class="header">
        <a href="#" class="logo">LearningManagement System</a>
        <div class="search-box">
            <input class="search-txt" type="text" placeholder="Type to search">
            <a href="#" class="search-btn">
                <ion-icon name="search" class="icon"></ion-icon>
            </a>
        </div>
        <nav class="navbar">
            <a href="#" class="active item" style="--i: 1">Home</a>
            <a href="#" class="item" style="--i: 2">About Us</a>
            <a href="#" class="item" style="--i: 3">Dashboard</a>
            <a href="{{ auth()->check() ? route('show.main') : route('login') }}" class="item" style="--i: 4">Forum</a>

            <a href="#" class="item" style="--i: 5">Login/Sign up</a>
        </nav>
    </header>

    <div class="pagecontent">
        @yield('content')
        
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    
</body>
</html>