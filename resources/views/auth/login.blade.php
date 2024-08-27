<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login with Role</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h2>Login</h2>
            <!-- Alert Messages -->
            <div class="mt-5">
                @if($errors->any())
                    <div class="col-12">
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @if(session()->has('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
            </div>

            <form method="POST" action="{{ route('login.post') }}">
                @csrf <!-- CSRF Token -->
                <div class="textbox">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="textbox">
                    <label for="password">Password</label>
                    <div class="password-container">
                        <input id="password" type="password" name="password" required autocomplete="new-password">
                        <span class="toggle-password" onclick="togglePasswordVisibility('password')">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    @error('password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="textbox">
                    <label for="remember">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                    </label>
                </div>

                <div class="textbox">
                    <button type="submit">Login</button>
                </div>

                <div>
                    <a href="{{ route('forget.password') }}">Forget Password</a>
                </div>
            </form>

            <p>Haven't got an account? <a href="{{ route('register.options') }}">Register now</a></p>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(id) {
            const passwordInput = document.getElementById(id);
            const toggleIcon = passwordInput.nextElementSibling.querySelector('i');

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');


                
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>
</html>
