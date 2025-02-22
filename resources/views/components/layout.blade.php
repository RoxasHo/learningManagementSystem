<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp"rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/nav.css') }}" >
    <script src="{{ asset('js/nav.js') }}"></script>
</head>
<body>
    <header>
        <!--Navigation Bar-->
        <nav class="navbar">
            <section class="navbar-brand">
                <div class="menuicon">
                    <span class="material-icons menu-icon" id="menuIcon"> menu </span>
                </div>
                <a href="/">
                    <img src="/images/superkianho.png" width="150" height="80">
                </a>
            </section>
            <section class="navbar-menu">
                @if(Auth::check())
                    <a href="{{ route('profile.student', ['email' => Auth::user()->email]) }}" class="account-link">
                        <span class="account-name">Welcome, {{ Auth::user()->name }}!</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-button">Logout</button>
                    </form>
                @else
                    <!-- User is not logged in -->
                    <a href="{{ route('login') }}" class="login-button">Login</a>
                @endif

            </section>
        </nav>
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar">
            <div class="sidebar-menu">
                @if(optional(auth()->user())->role === 'Student')
                    <a href="/">Home</a>
                @endif
                @if(optional(auth()->user())->role === 'Student' || optional(auth()->user())->role === null)
                    <a href="{{ route('courses.index') }}">Courses</a>
                @endif
                @if(Auth::check())
                    @if(Auth::user()->role === 'Student')
                        <a href="{{ route('profile.student', ['email' => Auth::user()->email]) }}">Dashboard</a>
                    @elseif(Auth::user()->role === 'Teacher')
                        <a href="{{ route('profile.teacher', ['email' => Auth::user()->email]) }}">Dashboard</a>
                    @elseif(Auth::user()->role === 'Moderator')
                        <a href="{{ route('profile.moderator', ['email' => Auth::user()->email]) }}">Dashboard</a>
                    @elseif(Auth::user()->role === 'Superuser')
                        <a href="{{ route('profile.superuser', ['email' => Auth::user()->email]) }}">Dashboard</a>
                    @endif
                @endif
                <a href="{{ auth()->check() ? route('show.main') : route('login') }}">Forums</a>
            </div>
            <a class="toggle-btn" id="toggleSidebar">
                <span class="material-icons" style="color: #fff;";>menu</span>
            </a>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer class="lms-footer">
        <div class="footer-content">
            <div class="footer-section about">
                <h3>About Us</h3>
                <p>
                    We provide top-tier online education, helping learners develop skills and achieve their goals through a wide variety of courses across multiple disciplines.
                </p>
                <p>&copy; {{ date('Y') }} SuperKianHo. All Rights Reserved.</p>
            </div>
            
            <div class="footer-section links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="{{ url('/courses') }}">Browse Courses</a></li>
                    <li><a href="{{ url('/about') }}">About Us</a></li>
                    <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                    <li><a href="{{ url('/support') }}">Support</a></li>
                    <li><a href="{{ url('/privacy-policy') }}">Privacy Policy</a></li>
                </ul>
            </div>

            <div class="footer-section contact">
                <h3>Contact Us</h3>
                <p>Email: support@superkianho.com</p>
                <p>Phone: +1 (555) 123-4567</p>
            </div>

            <div class="footer-section social">
                <h3>Follow Us</h3>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-twitter"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
            </div>
        </div>
    </footer>

</body>
</html>
