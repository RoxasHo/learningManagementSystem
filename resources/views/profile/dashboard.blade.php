<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superuser Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Superuser Dashboard</h1>
        <div class="list-group">
            <!-- Link to Students page -->
            <a href="{{ route('superuser.students.active') }}" class="list-group-item list-group-item-action">Students</a>
            <!-- Link to Teachers page -->
            <a href="{{ route('superuser.teachers.pending') }}" class="list-group-item list-group-item-action">Teachers</a>
            <!-- Link to Moderators page -->
            <a href="{{ route('superuser.moderators.pending') }}" class="list-group-item list-group-item-action">Moderators</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
