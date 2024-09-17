<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Options</title>
    <link rel="stylesheet" href="{{ asset('css/register_options.css') }}">
</head>
<body>
    <div class="registration-options">
        <h2>Choose Your Option to Register</h2>
        <button onclick="window.location.href='/register/student'">Register As Student</button>
        <button onclick="window.location.href='/register/teacher'">Register As Teacher</button>
        <button onclick="window.location.href='/register/moderator'">Register As Moderator</button>
        <p><a href="{{ route('login') }}">Back to Login Page</a></p>
    </div>
</body>
</html>
