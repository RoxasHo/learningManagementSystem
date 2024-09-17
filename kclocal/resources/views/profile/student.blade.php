<!-- resources/views/profile/student.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('profile.updateStudentPicture') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="studentPicture" class="profile-picture-label">
                    <img src="{{ $student->studentPicture ? asset('storage/' . $student->studentPicture) : asset('images/default-profile.png') }}" alt="Student Picture" class="profile-picture">
                    <input type="file" id="studentPicture" name="studentPicture" accept="image/*" class="profile-picture-input">
                    <span class="edit-icon">&#9998;</span>
                </label>
                <button type="submit" class="save-picture-button">Save Picture</button>
            </form>
            
            <div class="profile-info">
                <h2>{{ $student->name }}</h2>
                <p>Student ID: {{ $student->studentID }}</p>
                <p>Email: {{ $student->user->email }}</p>
                <a href="{{ route('profile.editStudent', $student->user->email) }}" class="btn btn-primary">Edit Profile</a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>

        <div class="profile-content">
            <div class="profile-section">
                <h3>Interests</h3>
                <p>{{ $student->interest ?? 'No interests available.' }}</p>
            </div>
            <div class="profile-section">
                <h3>Progress</h3>
                <p>{{ $student->progress ?? 'No progress available.' }}</p>
            </div>
        </div>
    </div>
</body>
</html>
