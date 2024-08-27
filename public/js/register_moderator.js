document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registerModeratorForm');
    const passwordInput = document.getElementById('password');
    const genderInputs = document.querySelectorAll('input[name="gender"]');
    const dateOfBirthInput = document.getElementById('dateOfBirth');
    const contactNumberInput = document.getElementById('contactNumber');
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

    function validateContactNumber() {
        const contactNumber = contactNumberInput.value.trim();
        const isNumeric = /^\d+$/.test(contactNumber); // Check if only numbers
        const validLength = contactNumber.length >= 10; // Check minimum length requirement

        if (!isNumeric) {
            
            document.getElementById('contactNumberError').textContent = 'Contact Number must contain only numbers';
            return false;
        } else if (!validLength) {
            document.getElementById('contactNumberError').textContent = 'Contact Number must be at least 10 digits long';
            return false;
        }

        document.getElementById('contactNumberError').textContent = ''; // Clear the error if validation passes
        return true;
    }

    passwordInput.addEventListener('input', validatePassword);
    contactNumberInput.addEventListener('blur', validateContactNumber); // Validate on blur

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

        if (!validateContactNumber()) { // Check contact number
            document.getElementById('contactNumber').textContent = 'Contact Number is required';

            isValid = false;
        }

        if (isValid) {
            const formData = new FormData(this);

            axios.post(form.action, formData)
                .then(response => {
                    if (response.data.redirect) {
                        window.location.href = response.data.redirect;
                    } else {
                        window.location.href = routes.login; // Use the route defined in the Blade template
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

            axios.post(routes.validate, formData)
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
