<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collect Points</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <!-- Modal -->
        <div class="modal fade show" id="pointModal" tabindex="-1" aria-labelledby="pointModalLabel" aria-hidden="true" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pointModalLabel">Daily Login Bonus</h5>
                        <a href="{{ route('profile.student', ['email' => Auth::user()->email]) }}" class="btn-close"></a>
                    </div>
                    <div class="modal-body">
                        Congratulations! You have collected 10 points for logging in today.
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('profile.student', ['email' => Auth::user()->email]) }}" class="btn btn-primary">Close</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
