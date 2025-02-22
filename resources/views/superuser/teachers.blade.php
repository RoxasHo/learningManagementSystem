<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Teachers - {{ ucfirst($status_type) }}</h1>

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $status_type === 'pending' ? 'active' : '' }}" href="{{ route('superuser.teachers.pending') }}">Pending</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status_type === 'rejected' ? 'active' : '' }}" href="{{ route('superuser.teachers.rejected') }}">Rejected</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status_type === 'active' ? 'active' : '' }}" href="{{ route('superuser.teachers.active') }}">Active</a>
            </li>
        </ul>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Rejection Reason</th>
                    <th>Information</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($teachers as $teacher)
                    <tr>
                        <td>{{ $teacher->id }}</td>
                        <td>{{ $teacher->name }}</td>
                        <td>{{ $teacher->email }}</td>
                        <td>{{ $teacher->status }}</td>
                        <td>{{ $teacher->rejection_reason ?? 'N/A' }}</td>
                        <td>
                            <button class="btn btn-info" onclick="showTeacherInfo({{ $teacher->id }})">View Info</button>
                        </td>
                        <td>
                            @if ($status_type === 'pending')
                                <form method="POST" action="{{ route('update_user_status') }}" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$teacher->id}}">
                                    <input type="hidden" name="status" value="{{$status_type}}">
                                    <button type="submit" class="btn btn-success">Activate</button>
                                </form>
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal" onclick="setRejectUserId({{ $teacher->id }})">Reject</button>
                            @elseif ($status_type === 'rejected')
                                <form method="POST" action="{{ route('update_user_status', ['id' => $teacher->id, 'status' => 'active']) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Activate</button>
                                </form>
                            @elseif ($status_type === 'active')
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal" onclick="setRejectUserId({{ $teacher->id }})">Reject</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-between mt-3">
            {{ $teachers->links() }}
        </div>

        <a href="{{ route('superuser.dashboard') }}" class="btn btn-primary mt-3">Return to Dashboard</a>
    </div>

    <!-- Teacher Info Modal -->
    <div class="modal fade" id="teacher-info-modal" tabindex="-1" aria-labelledby="teacher-info-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="teacher-info-modal-label">Teacher Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="teacher-info-content"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="rejectForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="user_id" id="rejectUserId">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel">Reject User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Rejection Reason</label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Submit Rejection</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
function showTeacherInfo(teacherId) {
    fetch(`/superuser/teachers/info/${teacherId}`)
        .then(response => response.json())
        .then(data => {
            // Create content for the modal
            let content = `
                <p><strong>Teacher Picture:</strong></p>
                <img src="${data.teacherPicture}" alt="Teacher Picture" class="img-fluid" style="max-width: 100%;">

                <p><strong>Name:</strong> ${data.name}</p>
                <p><strong>Email:</strong> ${data.email}</p>
                <p><strong>Gender:</strong> ${data.gender}</p>
                <p><strong>Date of Birth:</strong> ${data.dateOfBirth}</p>
                <p><strong>Contact Number:</strong> ${data.contactNumber}</p>
                <p><strong>Years of Experience:</strong> ${data.yearsOfExperience}</p>
                <p><strong>Certification:</strong> <a href="${data.certification}" target="_blank" class="btn btn-primary">View Certification</a></p>
                <p><strong>Identity Proof:</strong> <a href="${data.identityProof}" target="_blank" class="btn btn-primary">View Identity Proof</a></p>
            `;
            document.getElementById('teacher-info-content').innerHTML = content;
            var teacherModal = new bootstrap.Modal(document.getElementById('teacher-info-modal'));
            teacherModal.show();
        });
}

function setRejectUserId(userId) {
    document.getElementById('rejectUserId').value = userId;
    document.getElementById('rejectForm').action = `/superuser/reject_user/${userId}`;
}
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
