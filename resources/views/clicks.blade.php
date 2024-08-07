<!DOCTYPE html>
<html>
<head>
    <title>Click Counter</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link
      href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp"
      rel="stylesheet"
    />
    <style>
        /* Global CSS rules */
        body {
            margin: 0;
        }

        .material-icons,
        .material-icons-outlined {
            font-size: 35px;
            cursor: pointer;
            color: #5f6368;
        }

        .main {
            padding: 30px;
            position: relative;
        }

        h1,
        h2,
        h3 {
            margin: 0;
        }

        /* Navbar CSS Rules */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 0.0625rem solid #e0e0e0;
            height: 85px;
            padding: 20px;
            box-sizing: border-box;
        }

        .brand-logo {
            width: 74px;
            height: 24px;
        }

        .brand-text {
            font-family: "Product Sans", Arial, Helvetica, sans-serif;
            font-size: 1.38125rem;
        }

        .navbar-brand {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            width: 250px;
        }

        .navbar-menu {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            width: 200px;
        }

        .big-img {
            font-size: 45px;
        }

        .secondary-header {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            font-family: "Google Sans", Roboto, Arial, sans-serif;
            font-weight: 500;
            letter-spacing: 0.25px;
        }

        .secondary-icon-container {
            margin: 5px 15px 5px 5px;
            padding: 10px;
            border-radius: 10px;
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            cursor: pointer;
        }

        .secondary-icon-container:hover {
            
        }

        .secondary-icon-text {
            display: inline-block;
            margin-left: 10px;
            color: #1a73e8;
            font-weight: 500;
        }

        .floating-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        .floating-page {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .floating-form {
            background: white;
            padding: 20px 20px 40px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .addConButton{
            background-color: #1967D2;
            color: #fff;
            cursor: pointer;
            border: 2px solid #1967D2;
            border-radius: 5px;
            padding: 4px 12px;
        }

        .container {
            display: inline-block;
            border: 0.0625rem solid #dadce0;
            height: 21.355rem;
            width: 20rem;
            margin: 15px 15px 0 15px;
            text-align: left;
            border-radius: 13px;
            font-family: "Google Sans", Roboto, Arial, sans-serif;
            color: #fff;
        }
        .conheader{
            position: relative;
            padding: 15px;
            height: 30%;
            background: url("https://www.gstatic.com/classroom/themes/img_bookclub.jpg")
                no-repeat;
            background-position: center;
            background-size: cover;
            border-radius: 13px 13px 0 0;
        }
        .card-body {
            height: 45%;
        }
        .card-footer {
            border-top: 0.0625rem solid #dadce0;
            height: 17%;
            text-align: right;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 10px;
        }
        .increment-button{
            background-color: #1967D2;
            color: #fff;
            cursor: pointer;
            border: 2px solid #1967D2;
            border-radius: 5px;
            padding: 4px 12px;
            margin-right: 15px;
        }
        .add-container, .reset-container, .show-form-container {
            margin: 20px 0;
        }
        .top-counters {
            margin-top: 40px;
        }
        .top-container {
            background-color: #e0e0e0;
            padding: 10px;
            margin-bottom: 10px;
        }
        .blur-background {
            filter: blur(5px);
            transition: filter 0.3s ease;
        }
        .blur-background > * {
            pointer-events: none; /* Prevent interactions with blurred elements */
        }

        /* Floating form styles */
        #floatingForm {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            max-width: 500px;
            background: white;
            border: 1px solid #ddd;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        #floatingForm button.close {
            background: #f44336;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }

        #floatingForm button.submit {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }

        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>
<header>
    <nav class="navbar">
        <section class="navbar-brand">
            <span class="material-icons menu-icon"> menu </span>
          <!-- <img
            class="brand-logo"
            src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Google_2015_logo.svg/368px-Google_2015_logo.svg.png"
            alt="Google Logo"
          /> --><span class="brand-text">Website Name</span>
        </section>
        <section class="navbar-menu">
            <span class="material-icons add-icon"> add </span>
            <span class="material-icons apps-icon"> apps </span>
            <span class="material-icons big-img account-icon">
                account_circle
            </span>
        </section>
    </nav>
</header>
<body>
    <section class="secondary-header">
            <div class="secondary-icon-container">
                <span class="material-icons-outlined"> fact_check </span>
                <span class="secondary-icon-text">To do</span>
            </div>
            <div class="secondary-icon-container">
                <span class="material-icons calender-icon"> calendar_today </span>
                <span class="secondary-icon-text">Calender</span>
        </div>
    </section>
<div id="blurContent">
    @foreach ($counters as $counter)
        <div class="container" id="container{{ $counter->name }}">
            <div class="conheader">{{ $counter->name }}: {{ $counter->counter }}</div>
            <div class="card-body"></div>
            <div class="card-footer">
                <button class="increment-button" data-name="{{ $counter->name }}">Increment</button>
                <!-- <span class="material-icons-outlined assignment-icon">
                assignment_ind
                </span>
                <span class="material-icons-outlined folder-icon"> folder </span> -->
            </div>
        </div>
    @endforeach

    <div class="add-container">
        <form id="addContainerForm">
            <input type="text" name="name" placeholder="New Container Name" required>
            <button type="submit">Add Container</button>
        </form>
    </div>

    <div class="reset-container">
        <button id="resetButton">Reset All Counters</button>
    </div>
    
    <div class="top-counters">
        <h2>Top 3 Most Clicked Containers</h2><h6>(Based on data from the database, updates per min)</h6>
        @foreach ($topCounters as $topCounter)
            <div class="top-container">
                {{ $topCounter->name }}: {{ $topCounter->counter }}
            </div>
        @endforeach
    </div>

    <div class="show-form-container">
        <button id="showFormButton">Open Questionnaire</button>
    </div>
</div>

<div class="floating-button" id="add-container-button">+</div>

<!-- Floating Page -->
<div class="floating-page" id="add-container-page">
    <div class="floating-form">
        <p style="font-size:160%">Add New Container</p>
        <form id="add-container-form">
            <div class="form-group">
                <label for="container-name">Container Name : </label>
                <input type="text" class="form-control" id="container-name" name="name" required>
            </div>
            <button type="submit" class="addConButton">Add Container</button>
        </form>
    </div>
</div>

<!-- Floating Form -->
<div id="overlay"></div>
    <div id="floatingForm">
        <button class="close">Close</button>
        <h2>Questionnaire</h2>
        <form id="questionnaireForm">
            <label for="favoriteContainer">Q1. Which container do you like the most?</label>
            <select name="favoriteContainer" id="favoriteContainer">
                @foreach ($counters as $counter)
                    <option value="{{ $counter->name }}">{{ $counter->name }}</option>
                @endforeach
            </select>
            <br><br>
            <label for="question2">Q2. Would you recommend us to others?</label>
            <select name="question2" id="question2">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
            <br><br>
            <button type="button" class="submit">Submit</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Increment button click handler
            $('.increment-button').click(function() {
                let name = $(this).data('name');

                $.ajax({
                    url: '/click/increment',
                    method: 'POST',
                    data: { name: name },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    }
                });
            });

            // Show the form when the floating button is clicked
            $('#add-container-button').click(function() {
                $('#add-container-page').fadeIn();
                $('#main-content').addClass('blur-background');
            });

            // Hide the form when clicking outside of it
            $('#add-container-page').click(function(event) {
                if ($(event.target).is('#add-container-page')) {
                    $(this).fadeOut();
                    $('#main-content').removeClass('blur-background');
                }
            });

            // Handle form submission
            $('#add-container-form').submit(function(event) {
                event.preventDefault();
                let containerName = $('#container-name').val();

                $.ajax({
                    url: '/containers/add',
                    method: 'POST',
                    data: { name: containerName },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload(); // Reload to update the page
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText); // Log any AJAX errors
                    }
                });
            });

            $('#resetButton').click(function() {
                $.ajax({
                    url: '/click/reset',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    }
                });
            });

            $('#showFormButton').click(function() {
                $('#overlay').show();
                $('#floatingForm').show();
                $('#blurContent').addClass('blur-background'); // Apply blur effect
            });

            $('#floatingForm .close').click(function() {
                $('#overlay').hide();
                $('#floatingForm').hide();
                $('#blurContent').removeClass('blur-background'); // Remove blur effect
            });

            $('#questionnaireForm .submit').click(function() {
                const formData = $('#questionnaireForm').serialize();

                $.ajax({
                    url: '/click/questionnaire',
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#overlay').hide();
                        $('#floatingForm').hide();
                        $('#blurContent').removeClass('blur-background'); // Remove blur effect
                        if (response.success) {
                            location.reload();
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
