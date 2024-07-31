<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Profile</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profileTeacher.css') }}"> 
</head>
<body>
    <div class="profile-container">
        <h2>Teacher Profile</h2>
        <div class="profile-info">
            <div class="avatar">
                <img src="{{ asset('images/avatar.jpg') }}" alt="Teacher Avatar">
            </div>
            <div class="details">
                <p><strong>Name:</strong> {{ $teacher->name }}</p>
                <p><strong>Email:</strong> {{ $teacher->email }}</p>
                <p><strong>Joined:</strong> {{ $teacher->created_at->format('M d, Y') }}</p>
               
            </div>
        </div>
        <div class="courses">
            <h3>Teaching Courses</h3>
            <ul>
               
                @foreach ($teacher->courses as $course)
                    <li>{{ $course->title }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</body>
</html>
