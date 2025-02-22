<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
/* 调整分页箭头按钮的大小 */
.page-link {
    font-size: 12px; /* 调整字体大小 */
    padding: 5px 10px; /* 调整按钮的上下左右间距 */
}

.page-item .page-link {
    width: 40px; /* 调整按钮宽度 */
    height: 40px; /* 调整按钮高度 */
    display: flex;
    align-items: center;
    justify-content: center;
}
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Students - {{ ucfirst($status_type) }}</h1>

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $status_type === 'active' ? 'active' : '' }}" href="{{ route('superuser.students.active') }}">Active</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status_type === 'rejected' ? 'active' : '' }}" href="{{ route('superuser.students.rejected') }}">Rejected</a>
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
                @foreach ($students as $student)
                    <tr>
                        <td>{{ $student->id }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->status }}</td>
                        <td>{{ $student->rejection_reason ?? 'N/A' }}</td>
                        <td>
                            <button class="btn btn-info" onclick="showStudentInfo({{ $student->id }})">View Info</button>
                        </td>
                        <td>
                            @if ($status_type === 'rejected')
                                <form method="POST" action="{{ route('su_update_user_status', ['id' => $student->id, 'status' => 'active']) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Activate</button>
                                </form>
                            @elseif ($status_type === 'active')
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal" 
                                        onclick="setRejectUserId({{ $student->id }})">Reject</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-between mt-3">
            {{ $students->links() }}
        </div>

        <a href="{{ route('superuser.dashboard') }}" class="btn btn-primary mt-3">Return to Dashboard</a>
    </div>

    <!-- Student Info Modal -->
    <div class="modal fade" id="student-info-modal" tabindex="-1" aria-labelledby="student-info-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="student-info-modal-label">Student Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="student-info-content"></div>
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
                        <button type="submit" class="btn btn-danger">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showStudentInfo(studentId) {
            fetch(`/superuser/students/info/${studentId}`)
                .then(response => response.json())
                .then(data => {
                    let content = `
                        <p><strong>Email:</strong> ${data.email}</p>
                        <p><strong>Name:</strong> ${data.name}</p>
                        <p><strong>Gender:</strong> ${data.gender}</p>
                        <p><strong>Date of Birth:</strong> ${data.dateOfBirth}</p>
                        <p><strong>Contact Number:</strong> ${data.contactNumber}</p>
                    `;
                    document.getElementById('student-info-content').innerHTML = content;
                    var studentModal = new bootstrap.Modal(document.getElementById('student-info-modal'));
                    studentModal.show();
                });
        }

        function setRejectUserId(userId) {
            document.getElementById('rejectUserId').value = userId;
            document.getElementById('rejectForm').action = '/superuser/reject_user/' + userId;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
