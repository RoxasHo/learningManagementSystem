<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register As Student</title>
    <link rel="stylesheet" href="{{ asset('css/register_student.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <div class="registration-form">
        <h2>Registration As Student</h2>
        <form id="registerStudentForm" action="{{ route('student.register') }}" method="POST">
            @csrf
            <label for="email">E-Mail:</label>
            <input type="email" id="email" name="email" required>
            <span id="emailError" class="text-danger"></span>

            <label for="fullName">Full Name:</label>
            <input type="text" id="fullName" name="name" required>
            <span id="nameError" class="text-danger"></span>

            <div class="gender">
                <label>Gender:</label>
                <label for="male">
                    <input type="radio" id="male" name="gender" value="Male"> Male
                </label>
                <label for="female">
                    <input type="radio" id="female" name="gender" value="Female"> Female
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

            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" id="confirmPassword" name="password_confirmation" required>
            <span id="confirmPasswordError" class="text-danger"></span>

            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registerStudentForm');
    const passwordInput = document.getElementById('password');
    const genderInputs = document.querySelectorAll('input[name="gender"]');
    const dateOfBirthInput = document.getElementById('dateOfBirth');
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
        const numberValid = /[0-9]/.test(password);
        const specialCharValid = /[!@#$%^&*]/.test(password);

        criteria.length.classList.toggle('valid', lengthValid);
        criteria.uppercase.classList.toggle('valid', uppercaseValid);
        criteria.lowercase.classList.toggle('valid', lowercaseValid);
        criteria.number.classList.toggle('valid', numberValid);
        criteria.specialChar.classList.toggle('valid', specialCharValid);

        return lengthValid && uppercaseValid && lowercaseValid && numberValid && specialCharValid;
    }

    function validateGender() {
        let genderSelected = false;
        genderInputs.forEach(input => {
            if (input.checked) {
                genderSelected = true;
            }
        });
        return genderSelected;
    }

    function validateDateOfBirth() {
        return dateOfBirthInput.value !== '';
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

        if (!validateGender()) {
            document.getElementById('genderError').textContent = 'Gender is required';
            isValid = false;
        }

        if (!validateDateOfBirth()) {
            document.getElementById('dateOfBirthError').textContent = 'Date of Birth is required';
            isValid = false;
        }

        if (isValid) {
            const formData = new FormData(this);

            axios.post('{{ route('student.register') }}', formData)
    .then(response => {
        if (response.data.redirect) {
            window.location.href = response.data.redirect;
        }else{
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

            axios.post('{{ route('student.validate') }}', formData)
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
