<!-- resources/views/auth/registerTeacher.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register As Teacher</title>
    <link rel="stylesheet" href="{{ asset('css/register_teacher.css') }}">
</head>
<body>
    <div class="registration-form">
        <h2>Registration As Teacher</h2>
        <form action="{{ route('teacher.register') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="email">E-Mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="contactNumber">Contact Number:</label>
            <input type="text" id="contactNumber" name="contactNumber" required>

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

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" id="confirmPassword" name="password_confirmation" required>

            <label for="certification">Submit Proof of Certification:</label>
            <input type="file" id="certification" name="certification" required>

            <label for="identityProof">Identity Proof:</label>
            <input type="file" id="identityProof" name="identityProof" required>

            <label for="picture">Profile Picture:</label>
            <input type="file" id="picture" name="picture" required>

            <label for="yearsOfExperience">Years of Experience:</label>
            <input type="number" id="yearsOfExperience" name="yearsOfExperience" required>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
