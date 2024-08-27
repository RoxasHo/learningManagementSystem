<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Teacher Profile</title>
    <link rel="stylesheet" href="{{ asset('css/profileTeacher.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                <span class="error-message text-danger"></span>
            </div>
            <div class="form-group">
                <label for="contactNumber">Contact Number:</label>
                <input type="text" id="contactNumber" name="contactNumber" class="form-control" value="{{ $teacher->user->contactNumber }}" required>
                <span class="error-message text-danger"></span>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" class="form-control" required>
                    <option value="Male" {{ $teacher->user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ $teacher->user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
                <span class="error-message text-danger"></span>
            </div>
            <div class="form-group">
                <label for="dateOfBirth">Date of Birth:</label>
                <input type="date" id="dateOfBirth" name="dateOfBirth" class="form-control" value="{{ $teacher->user->dateOfBirth }}" required>
                <span class="error-message text-danger"></span>
            </div>
            <div class="form-group">
                <label for="yearsOfExperience">Years of Experience:</label>
                <input type="number" id="yearsOfExperience" name="yearsOfExperience" class="form-control" value="{{ $teacher->yearsOfExperience }}" required>
                <span class="error-message text-danger"></span>
            </div>
            <div class="form-group">
                <label for="certification">Certification (PDF, DOC, DOCX):</label>
                <input type="file" id="certification" name="certification" class="form-control" accept=".pdf,.doc,.docx">
                <span class="error-message text-danger"></span>
            </div>
            <div class="form-group">
                <label for="identityProof">Identity Proof (PDF, DOC, DOCX):</label>
                <input type="file" id="identityProof" name="identityProof" class="form-control" accept=".pdf,.doc,.docx">
                <span class="error-message text-danger"></span>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('input, select').on('input change', function() {
                validateField($(this));
            });

            function validateField(element) {
                let value = element.val();
                let id = element.attr('id');
                let errorMessage = '';

                switch (id) {
                    case 'name':
                        if (value === '' || !/^[a-zA-Z\s]+$/.test(value)) {
                            errorMessage = 'Please enter a valid name.';
                        }
                        break;
                    case 'contactNumber':
                        if (value === '' || !/^\d{10,15}$/.test(value)) {
                            errorMessage = 'Please enter a valid contact number.';
                        }
                        break;
                    case 'dateOfBirth':
                        if (new Date(value) >= new Date()) {
                            errorMessage = 'Date of birth must be in the past.';
                        }
                        break;
                    case 'yearsOfExperience':
                        if (value < 0 || value > 50) {
                            errorMessage = 'Years of experience must be between 0 and 50.';
                        }
                        break;
                    case 'certification':
                        if (element[0].files[0] && element[0].files[0].size > 2048 * 1024) {
                            errorMessage = 'Certification file size must not exceed 2MB.';
                        }
                        break;
                    case 'identityProof':
                        if (element[0].files[0] && element[0].files[0].size > 2048 * 1024) {
                            errorMessage = 'Identity Proof file size must not exceed 2MB.';
                        }
                        break;
                }

                if (errorMessage) {
                    showError(element, errorMessage);
                } else {
                    clearError(element);
                }
            }

            function showError(element, message) {
                let errorElement = element.next('.error-message');
                if (!errorElement.length) {
                    errorElement = $('<span class="error-message text-danger"></span>').insertAfter(element);
                }
                errorElement.text(message);
                element.addClass('is-invalid');
            }

            function clearError(element) {
                element.removeClass('is-invalid');
                element.next('.error-message').remove();
            }
        });
    </script>
</body>
</html>
