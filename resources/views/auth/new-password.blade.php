<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
        }

        .password-rules {
            margin-bottom: 10px;
            font-size: 0.9em;
        }

        .password-rules li {
            color: red;
        }

        .password-rules li.valid {
            color: green;
        }

        .is-invalid {
            border-color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-box" style="width: 500px">
            <h2>Reset Password</h2>
            <h3>Enter your new password below.</h3>
            
            <!-- Alert Messages -->
            <div class="mt-5">
                @if($errors->any())
                    <div class="col-12">
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @if(session()->has('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if(session()->has('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
            </div>
            
            <form method="POST" action="{{ route('reset.password.post') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="textbox">
                    <label for="password">Enter Password</label>
                    <input id="password" type="password" name="password" required>
                    <span class="error-message"></span>
                </div>

                <!-- Password Criteria Section -->
                <div id="passwordCriteria">
                    <ul class="password-rules">
                        <li id="lengthCriteria">At least 8 characters</li>
                        <li id="uppercaseCriteria">At least one uppercase letter</li>
                        <li id="lowercaseCriteria">At least one lowercase letter</li>
                        <li id="numberCriteria">At least one number</li>
                        <li id="specialCharCriteria">At least one special character (!@#$%^&*)</li>
                    </ul>
                </div>

                <div class="textbox">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                    <span class="error-message"></span>
                </div>

                <div class="textbox">
                    <button type="submit">Reset Password</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Updated JS Validation -->
    <script>
        $(document).ready(function() {
            $('#password').on('input', function() {
                validatePassword();
            });

            $('#password_confirmation').on('input', function() {
                validateConfirmation();
            });

            function validatePassword() {
                let password = $('#password').val();
                let lengthRule = password.length >= 8;
                let uppercaseRule = /[A-Z]/.test(password);
                let lowercaseRule = /[a-z]/.test(password);
                let numberRule = /[0-9]/.test(password);
                let specialCharRule = /[!@#$%^&*]/.test(password);

                updateRuleState('#lengthCriteria', lengthRule);
                updateRuleState('#uppercaseCriteria', uppercaseRule);
                updateRuleState('#lowercaseCriteria', lowercaseRule);
                updateRuleState('#numberCriteria', numberRule);
                updateRuleState('#specialCharCriteria', specialCharRule);

                if (lengthRule && uppercaseRule && lowercaseRule && numberRule && specialCharRule) {
                    clearError($('#password'));
                } else {
                    showError($('#password'), 'Password does not meet all requirements.');
                }
            }

            function validateConfirmation() {
                let password = $('#password').val();
                let confirmation = $('#password_confirmation').val();

                if (password !== confirmation) {
                    showError($('#password_confirmation'), 'Passwords do not match.');
                } else {
                    clearError($('#password_confirmation'));
                }
            }

            function updateRuleState(selector, isValid) {
                let element = $(selector);
                if (isValid) {
                    element.addClass('valid');
                    element.removeClass('invalid');
                } else {
                    element.removeClass('valid');
                    element.addClass('invalid');
                }
            }

            function showError(element, message) {
                let errorElement = element.next('.error-message');
                errorElement.text(message);
                element.addClass('is-invalid');
            }

            function clearError(element) {
                let errorElement = element.next('.error-message');
                errorElement.text('');
                element.removeClass('is-invalid');
            }
        });
    </script>
</body>
</html>
