document.addEventListener('DOMContentLoaded', function() {
    const forms = ['registerStudentForm', 'registerModeratorForm', 'registerTeacherForm'];

    forms.forEach(function(formId) {
        const form = document.getElementById(formId);
        if (!form) return;

        const passwordInput = form.querySelector('#password');
        const confirmPasswordInput = form.querySelector('#confirmPassword');

        const lengthCriteria = document.getElementById('lengthCriteria');
        const uppercaseCriteria = document.getElementById('uppercaseCriteria');
        const numberCriteria = document.getElementById('numberCriteria');
        const specialCharCriteria = document.getElementById('specialCharCriteria');
        const passwordError = document.getElementById('passwordError');

        const uppercasePattern = /^[A-Z]/;
        const numberPattern = /\d/;
        const specialCharPattern = /[!@#$%^&*]/;

        passwordInput.addEventListener('input', function() {
            const password = passwordInput.value;
            let errorMessage = '';

            if (password.length >= 6) {
                lengthCriteria.classList.add('valid');
                lengthCriteria.classList.remove('invalid');
            } else {
                lengthCriteria.classList.add('invalid');
                lengthCriteria.classList.remove('valid');
                errorMessage += 'Password must be at least 6 characters long. ';
            }

            if (uppercasePattern.test(password)) {
                uppercaseCriteria.classList.add('valid');
                uppercaseCriteria.classList.remove('invalid');
            } else {
                uppercaseCriteria.classList.add('invalid');
                uppercaseCriteria.classList.remove('valid');
                errorMessage += 'Password must start with an uppercase letter. ';
            }

            if (numberPattern.test(password)) {
                numberCriteria.classList.add('valid');
                numberCriteria.classList.remove('invalid');
            } else {
                numberCriteria.classList.add('invalid');
                numberCriteria.classList.remove('valid');
                errorMessage += 'Password must contain at least one number. ';
            }

            if (specialCharPattern.test(password)) {
                specialCharCriteria.classList.add('valid');
                specialCharCriteria.classList.remove('invalid');
            } else {
                specialCharCriteria.classList.add('invalid');
                specialCharCriteria.classList.remove('valid');
                errorMessage += 'Password must contain at least one special character (!@#$%^&*). ';
            }

            passwordError.textContent = errorMessage;
        });

        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission if criteria are not met

            const isValidPassword = lengthCriteria.classList.contains('valid') &&
                                    uppercaseCriteria.classList.contains('valid') &&
                                    numberCriteria.classList.contains('valid') &&
                                    specialCharCriteria.classList.contains('valid');

            if (!isValidPassword) {
                passwordInput.setCustomValidity('Password does not meet the required criteria.');
                return;
            } else {
                passwordInput.setCustomValidity('');
            }

            if (form.checkValidity()) {
                form.submit();  // Submit the form if all validations are passed
            }
        });
    });
});
