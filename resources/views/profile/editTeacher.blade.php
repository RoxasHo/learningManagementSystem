<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Teacher Profile</title>
    <link rel="stylesheet" href="{{ asset('css/profileTeacher.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="edit-profile-container">
        <h2>Edit Teacher Profile</h2>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.updateTeacher', ['email' => $teacher->user->email]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST') <!-- Ensure that we are using the POST method -->
            
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ $teacher->user->name }}" required>
            </div>
            <div class="form-group">
                <label for="contactNumber">Contact Number:</label>
                <input type="text" id="contactNumber" name="contactNumber" class="form-control" value="{{ $teacher->user->contactNumber }}" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" class="form-control" required>
                    <option value="Male" {{ $teacher->user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ $teacher->user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="dateOfBirth">Date of Birth:</label>
                <input type="date" id="dateOfBirth" name="dateOfBirth" class="form-control" value="{{ $teacher->user->dateOfBirth }}" required>
            </div>
            <div class="form-group">
                <label for="yearsOfExperience">Years of Experience:</label>
                <input type="number" id="yearsOfExperience" name="yearsOfExperience" class="form-control" value="{{ $teacher->yearsOfExperience }}" required>
            </div>
            <div class="form-group">
                <label for="certification">Certification (PDF, DOC, DOCX):</label>
                <input type="file" id="certification" name="certification" class="form-control">
            </div>
            <div class="form-group">
                <label for="identityProof">Identity Proof (PDF, DOC, DOCX):</label>
                <input type="file" id="identityProof" name="identityProof" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</body>
</html>
