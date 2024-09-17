<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register As Moderator</title>
    <link rel="stylesheet" href="{{ asset('css/register_moderator.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        // Pass Laravel routes to JavaScript
        const routes = {
            validate: "{{ route('moderator.validate') }}",
            login: "{{ route('login') }}"
        };
    </script>
</head>
<body>
    <div class="registration-form">
        <h2>Registration As Moderator</h2>
        <form id="registerModeratorForm" action="{{ route('moderator.register') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="email">E-Mail:</label>
            <input type="email" id="email" name="email" required>
            <span id="emailError" class="text-danger"></span>

            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>
            <span id="nameError" class="text-danger"></span>

            <div class="gender">
                <label for="gender">Gender:</label>
                <label for="male">
                    <input type="radio" id="male" name="gender" value="Male" required> Male
                </label>
                <label for="female">
                    <input type="radio" id="female" name="gender" value="Female" required> Female
                </label>
            </div>
            <span id="genderError" class="text-danger"></span>

            <label for="dateOfBirth">Date of Birth:</label>
            <input type="date" id="dateOfBirth" name="dateOfBirth" required>
            <span id="dateOfBirthError" class="text-danger"></span>

            <label for="contactNumber">Contact Number:</label>
            <input type="text" id="contactNumber" name="contactNumber" required>
            <span id="contactNumberError" class="text-danger"></span>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <span id="passwordError" class="text-danger"></span>

            <div id="passwordCriteria">
                <ul>
                    <li id="lengthCriteria">At least 8 characters</li>
                    <li id="uppercaseCriteria">At least one uppercase letter</li>
                    <li id="lowercaseCriteria">At least one lowercase letter</li>
                    <li id="numberCriteria">At least one number</li>
                    <li id="specialCharCriteria">At least one special character (!@#$%^&*)</li>
                </ul>
            </div>

            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
            <span id="confirmPasswordError" class="text-danger"></span>

            <label for="certification">Certification (Max 5MB, PDF only):</label>
            <input type="file" id="certification" name="certification" required accept="application/pdf">
            <span id="certificationError" class="text-danger"></span>

            <label for="identityProof">Identity Proof (Max 2MB, Image only):</label>
            <input type="file" id="identityProof" name="identityProof" required accept="image/*">
            <span id="identityProofError" class="text-danger"></span>

            <label for="moderatorPicture">Moderator Picture (Max 2MB, Image only):</label>
            <input type="file" id="moderatorPicture" name="moderatorPicture" required accept="image/*">
            <span id="moderatorPictureError" class="text-danger"></span>

            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('registerModeratorForm');
            const passwordInput = document.getElementById('password');
            const criteria = {
                length: document.getElementById('lengthCriteria'),
                uppercase: document.getElementById('uppercaseCriteria'),
                lowercase: document.getElementById('lowercaseCriteria'),
                number: document.getElementById('numberCriteria'),
                specialChar: document.getElementById('specialCharCriteria')
            };

            function validatePassword() {
                const password = passwordInput.value;
                const lengthValid = password.length >= 8;
                const uppercaseValid = /[A-Z]/.test(password);
                const lowercaseValid = /[a-z]/.test(password);
                const numberValid = /\d/.test(password);
                const specialCharValid = /[!@#$%^&*]/.test(password);

                criteria.length.classList.toggle('valid', lengthValid);
                criteria.uppercase.classList.toggle('valid', uppercaseValid);
                criteria.lowercase.classList.toggle('valid', lowercaseValid);
                criteria.number.classList.toggle('valid', numberValid);
                criteria.specialChar.classList.toggle('valid', specialCharValid);

                return lengthValid && uppercaseValid && lowercaseValid && numberValid && specialCharValid;
            }

            function validateFileSizeAndType(inputElement, maxSize, acceptedTypes, errorElement) {
                const file = inputElement.files[0];
                if (file) {
                    if (file.size > maxSize) {
                        errorElement.textContent = `File size exceeds ${maxSize / 1024 / 1024}MB limit.`;
                        return false;
                    }
                    if (!acceptedTypes.includes(file.type)) {
                        errorElement.textContent = `Invalid file type. Only ${acceptedTypes.join(", ")} are allowed.`;
                        return false;
                    }
                }
                errorElement.textContent = '';
                return true;
            }

            passwordInput.addEventListener('input', validatePassword);

            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const errorElements = document.querySelectorAll('.text-danger');
                errorElements.forEach(element => element.textContent = '');

                let isValid = true;

                if (!validatePassword()) {
                    document.getElementById('passwordError').textContent = 'Password does not meet criteria';
                    isValid = false;
                }

                if (!validateFileSizeAndType(document.getElementById('certification'), 5 * 1024 * 1024, ['application/pdf'], document.getElementById('certificationError'))) {
                    isValid = false;
                }
                if (!validateFileSizeAndType(document.getElementById('identityProof'), 2 * 1024 * 1024, ['image/jpeg', 'image/png'], document.getElementById('identityProofError'))) {
                    isValid = false;
                }
                if (!validateFileSizeAndType(document.getElementById('moderatorPicture'), 2 * 1024 * 1024, ['image/jpeg', 'image/png'], document.getElementById('moderatorPictureError'))) {
                    isValid = false;
                }

                if (isValid) {
                    const formData = new FormData(this);

                    axios.post('{{ route('moderator.register') }}', formData)
                        .then(response => {
                            if (response.data.redirect) {
                                window.location.href = response.data.redirect;
                            } else {
                                window.location.href = '{{ route('login') }}';
                            }
                        })
                        .catch(error => {
                            if (error.response && error.response.data.errors) {
                                const errors = error.response.data.errors;
                                for (const key in errors) {
                                    if (errors.hasOwnProperty(key)) {
                                        const errorElement = document.getElementById(key + 'Error');
                                        if (errorElement) {
                                            errorElement.textContent = errors[key][0];
                                        }
                                    }
                                }
                            }
                        });
                }
            });

            form.querySelectorAll('input, select, textarea').forEach(input => {
                input.addEventListener('blur', function() {
                    const formData = new FormData(form);
                    formData.append('field', this.name);

                    axios.post('{{ route('moderator.validate') }}', formData)
                        .then(response => {
                            const errorElement = document.getElementById(this.name + 'Error');
                            if (errorElement) {
                                errorElement.textContent = '';
                            }
                        })
                        .catch(error => {
                            if (error.response && error.response.data.errors) {
                                const errors = error.response.data.errors;
                                for (const key in errors) {
                                    if (errors.hasOwnProperty(key)) {
                                        const errorElement = document.getElementById(key + 'Error');
                                        if (errorElement) {
                                            errorElement.textContent = errors[key][0];
                                        }
                                    }
                                }
                            }
                        });
                });
            });
        });
    </script>
</body>
</html>
