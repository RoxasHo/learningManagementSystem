<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="container">
        <div class="login-box" style="width: 500px">
            <h2>Forgot Password</h2>
            <h3>We will send a link to your email, use that link to reset password.</h3>
            
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

                @if(session()->has('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
            </div>
            
            <form method="POST" action="{{ route('forget.password.post') }}">
                @csrf

                <div class="textbox">
                    <label for="email">Email address</label>
                    <input id="email" type="email" name="email" required>
                </div>

                <div class="textbox">
                    <button type="submit">Send Password Reset Link</button>
                </div>

                <p>Back to Login Page <a href="{{ route('login') }}">Login now</a></p>

            </form>
        </div>
    </div>
</body>
</html>
