<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderator Approval</title>
</head>
<body>
    <h2>Moderator Approval</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Contact Number</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendingModerators as $moderator)
                <tr>
                    <td>{{ $moderator->name }}</td>
                    <td>{{ $moderator->user->email }}</td>
                    <td>{{ $moderator->user->gender }}</td>
                    <td>{{ $moderator->user->dateOfBirth }}</td>
                    <td>{{ $moderator->user->contactNumber }}</td>
                    <td>
                        <form action="{{ route('superuser.approveModerator', ['id' => $moderator->moderatorID]) }}" method="POST">
                            @csrf
                            <button type="submit" style="background-color:green; color:white;">Approve</button>
                        </form>
                        <form action="{{ route('superuser.rejectModerator', ['id' => $moderator->moderatorID]) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" style="background-color:red; color:white;">Reject</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
