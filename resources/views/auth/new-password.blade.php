<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="container">
        <div class="login-box" style="width: 500px">
            <h2>Reset Password</h2>
            <h3>Enter your new password below.</h3>
            
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
            
            <form method="POST" action="{{ route('reset.password.post') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="textbox">
                    <label for="password">Enter Password</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <div class="textbox">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>

                <div class="textbox">
                    <button type="submit">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
