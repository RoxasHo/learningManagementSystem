<!-- resources/views/profile/student.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="{{ asset('css/profileStudent.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="profile-header-content">
                <div class="left-section">
                    <form action="{{ route('profile.updateStudentPicture') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="studentPicture" class="studentPicture-label">
                            <img src="{{ $student->studentPicture ? asset('storage/' . $student->studentPicture) : asset('images/default-profile.png') }}" alt="Student Picture" class="studentPicture">
                            <input type="file" id="studentPicture" name="studentPicture" accept="image/*" class="studentPicture-input">
                            <span class="edit-icon">&#9998;</span>
                        </label>
                        <br>
                        <button type="submit" class="save-picture-button">Save Picture</button>
                    </form>
                </div>
                <div class="right-section">
                    <h2>{{ $student->name }}</h2>
                    <p>Student ID: {{ $student->studentID }}</p>
                    <p>Email: {{ $student->user->email }}</p>
                    <p>register at: {{ $student->user->registeredAt }}</p>

                    <p>Birth Date: {{ \Carbon\Carbon::parse($student->user->dateOfBirth)->format('F j, Y') ?? 'Not provided' }}</p>
                    <p>Contact Number: {{ $student->user->contactNumber ? $student->user->contactNumber : 'Not provided' }}</p>
                    <p>Points: {{ $student->user->point }}</p>

                    <a href="{{ route('profile.editStudent', $student->user->email) }}" class="btn btn-primary">Edit Profile</a>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="profile-content">
            <div class="profile-section">
                <h3>Report Comment</h3>
                <p>{{ $student->reportComment ?? 'No report comment available' }}</p>
            </div>
            <div class="profile-section">
                <h3>Interest</h3>
                <p>{{ $student->interest ?? 'No interest available' }}</p>
            </div>
            <div class="profile-section">
                <h3>Progress</h3>
                <p>{{ $student->progress ?? 'No progress available' }}</p>
            </div>
        </div>
    </div>
</body>
</html>
