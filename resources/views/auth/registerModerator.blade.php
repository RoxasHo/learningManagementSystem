<!DOCTYPE html>
<html>
<head>
    <title>Register Moderator</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/registerModerator.css') }}">
    <link rel="stylesheet" href="{{ asset('css/register_moderator.css') }}">

</head>
<body>
    <form method="POST" action="{{ route('moderator.register') }}">
        @csrf
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" required>
        
        <label for="gender">Gender:</label>
        <input type="radio" id="male" name="gender" value="Male" required> Male
        <input type="radio" id="female" name="gender" value="Female" required> Female
        
         <label for="contactNumber">Contact Number:</label>
                <input type="text" id="contactNumber" name="contactNumber" required>
        
        <label for="dateOfBirth">Date of Birth:</label>
        <input type="date" id="dateOfBirth" name="dateOfBirth" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <label for="password_confirmation">Confirm Password:</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>
        
        <label for="preferredCode">Preferred Code:</label>
        <input type="text" id="preferredCode" name="preferredCode" required>
        
        <button type="submit">Register</button>
    </form>
</body>
</html>
