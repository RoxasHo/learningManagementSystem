<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Profile</title>
    <link rel="stylesheet" href="{{ asset('css/edit_profile.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="profile-container">
        <h2>Edit Student Profile</h2>
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
        <form action="{{ route('profile.updateStudent', ['email' => $student->user->email]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $student->name) }}" required>
                @if ($errors->has('name'))
                    <span class="error-message text-danger">{{ $errors->first('name') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" class="form-control" required>
                    <option value="Male" {{ old('gender', $student->user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', $student->user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
                @if ($errors->has('gender'))
                    <span class="error-message text-danger">{{ $errors->first('gender') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="dateOfBirth">Birth Date</label>
                <input type="date" id="dateOfBirth" name="dateOfBirth" class="form-control" value="{{ old('dateOfBirth', $student->user->dateOfBirth) }}" required>
                @if ($errors->has('dateOfBirth'))
                    <span class="error-message text-danger">{{ $errors->first('dateOfBirth') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="contactNumber">Contact Number</label>
                <input type="text" id="contactNumber" name="contactNumber" class="form-control" value="{{ old('contactNumber', $student->user->contactNumber) }}">
                @if ($errors->has('contactNumber'))
                    <span class="error-message text-danger">{{ $errors->first('contactNumber') }}</span>
                @endif
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
                        if (value !== '' && !/^\d{10,15}$/.test(value)) {
                            errorMessage = 'Please enter a valid contact number.';
                        }
                        break;
                    case 'dateOfBirth':
                        if (new Date(value) >= new Date()) {
                            errorMessage = 'Date of birth must be in the past.';
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
