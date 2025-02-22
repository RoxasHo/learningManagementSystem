<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Moderator Profile</title>
    <link rel="stylesheet" href="{{ asset('css/edit_moderator.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="edit-profile-container">
        <h2>Edit Moderator Profile</h2>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.updateModerator', ['email' => $moderator->user->email]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ $moderator->user->name }}" required>
                <span id="nameError" class="text-danger"></span>
            </div>
            <div class="form-group">
                <label for="contactNumber">Contact Number:</label>
                <input type="text" id="contactNumber" name="contactNumber" class="form-control" value="{{ $moderator->user->contactNumber }}" required>
                <span id="contactNumberError" class="text-danger"></span>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" class="form-control" required>
                    <option value="Male" {{ $moderator->user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ $moderator->user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
                <span id="genderError" class="text-danger"></span>
            </div>
            <div class="form-group">
                <label for="dateOfBirth">Date of Birth:</label>
                <input type="date" id="dateOfBirth" name="dateOfBirth" class="form-control" value="{{ $moderator->user->dateOfBirth }}" required>
                <span id="dateOfBirthError" class="text-danger"></span>
            </div>
            <div class="form-group">
                <label for="certification">Submit Proof of Certification (Max 5MB, PDF only):</label>
                <input type="file" id="certification" name="certification" class="form-control" accept=".pdf"  accept="application/pdf"> 
                <span id="certificationError" class="text-danger"></span>
            </div>
            <div class="form-group">
                <label for="identityProof">Identity Proof (Max 2MB, Image only(png,jpg,jpeg)):</label>
                <input type="file" id="identityProof" name="identityProof" class="form-control" accept=".jpg,.jpeg,.png"  accept="image/*">
                <span id="identityProofError" class="text-danger"></span>
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
                let file = element[0].files[0];
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
                    case 'certification':
                        if (file && file.size > 5 * 1024 * 1024) { // 5MB
                            errorMessage = 'Certification file size must not exceed 5MB.';
                        } else if (file && !/\.pdf$/.test(file.name)) {
                            errorMessage = 'Certification must be a PDF file.';
                        }
                        break;
                    case 'identityProof':
                        if (file && file.size > 2 * 1024 * 1024) { // 2MB
                            errorMessage = 'Identity Proof file size must not exceed 2MB.';
                        } else if (file && !/\.(jpg|jpeg|png)$/i.test(file.name)) {
                            errorMessage = 'Identity Proof must be an image file (JPG, JPEG, PNG).';
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
                let errorElement = $('#' + element.attr('id') + 'Error');
                if (!errorElement.length) {
                    errorElement = $('<span id="' + element.attr('id') + 'Error" class="text-danger"></span>').insertAfter(element);
                }
                errorElement.text(message);
                element.addClass('is-invalid');
            }

            function clearError(element) {
                element.removeClass('is-invalid');
                $('#' + element.attr('id') + 'Error').remove();
            }
        });
    </script>
</body>
</html>
