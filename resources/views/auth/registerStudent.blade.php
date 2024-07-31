<!-- resources/views/auth/registerStudent.blade.php -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register As Student</title>
        <link rel="stylesheet" href="{{ asset('css/register_student.css') }}">
    </head>
    <body>
        <div class="registration-form">
            <h2>Registration As Student</h2>
            <form action="{{ route('student.register') }}" method="POST">
                @csrf
                <label for="email">E-Mail:</label>
                <input type="email" id="email" name="email" required>

                <label for="fullName">Full Name:</label>
                <input type="text" id="fullName" name="name" required>

                <div class="gender">
                    <label>Gender:</label>
                    <label for="male">
                        <input type="radio" id="male" name="gender" value="Male"> Male
                    </label>
                    <label for="female">
                        <input type="radio" id="female" name="gender" value="Female"> Female
                    </label>
                </div>

                <label for="dateOfBirth">Date of Birth:</label>
                <input type="date" id="dateOfBirth" name="dateOfBirth" required>

                <label for="contactNumber">Contact Number:</label>
                <input type="text" id="contactNumber" name="contactNumber" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="password_confirmation" required>

                <button type="submit">Register</button>
            </form>

        </div>
    </body>
</html>
