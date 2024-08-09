<!-- resources/views/auth/registerModerator.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register As Moderator</title>
    <link rel="stylesheet" href="{{ asset('css/register_moderator.css') }}">
</head>
<body>
    <div class="registration-form">
        <h2>Registration As Moderator</h2>
        <form action="{{ route('moderator.register') }}" method="POST">
            @csrf
            <label for="email">E-Mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="gender">Gender:</label>
            <label for="male">
                <input type="radio" id="male" name="gender" value="Male" required> Male
            </label>
            <label for="female">
                <input type="radio" id="female" name="gender" value="Female" required> Female
            </label>

            <label for="contactNumber">Contact Number:</label>
            <input type="text" id="contactNumber" name="contactNumber" required>

            <label for="dateOfBirth">Date of Birth:</label>
            <input type="date" id="dateOfBirth" name="dateOfBirth" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>

            <label for="referralCode">Referral Code:</label>
            <input type="text" id="referralCode" name="referralCode" required>

            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
    </div>
</body>
</html>
