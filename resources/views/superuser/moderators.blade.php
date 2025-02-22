<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderators List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Moderators - {{ ucfirst($status_type) }}</h1>

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $status_type === 'pending' ? 'active' : '' }}" href="{{ route('superuser.moderators.pending') }}">Pending</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status_type === 'rejected' ? 'active' : '' }}" href="{{ route('superuser.moderators.rejected') }}">Rejected</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status_type === 'active' ? 'active' : '' }}" href="{{ route('superuser.moderators.active') }}">Active</a>
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
                @foreach ($moderators as $moderator)
                    <tr>
                        <td>{{ $moderator->id }}</td>
                        <td>{{ $moderator->name }}</td>
                        <td>{{ $moderator->email }}</td>
                        <td>{{ $moderator->status }}</td>
                        <td>{{ $moderator->rejection_reason ?? 'N/A' }}</td>
                        <td>
                            <button class="btn btn-info" onclick="showModeratorInfo({{ $moderator->id }})">View Info</button>
                        </td>
                        <td>
                            @if ($status_type === 'pending')
                                <form method="POST" action="{{ route('update_user_status', ['id' => $moderator->id, 'status' => 'active']) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Activate</button>
                                </form>
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal" onclick="setRejectUserId({{ $moderator->id }})">Reject</button>
                            @elseif ($status_type === 'rejected')
                                <form method="POST" action="{{ route('update_user_status', ['id' => $moderator->id, 'status' => 'active']) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Activate</button>
                                </form>
                            @elseif ($status_type === 'active')
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal" onclick="setRejectUserId({{ $moderator->id }})">Reject</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-between mt-3">
            {{ $moderators->links() }}
        </div>

        <a href="{{ route('superuser.dashboard') }}" class="btn btn-primary mt-3">Return to Dashboard</a>
    </div>

<!-- Moderator Info Modal -->
<div class="modal fade" id="moderator-info-modal" tabindex="-1" aria-labelledby="moderator-info-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="moderator-info-modal-label">Moderator Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="moderator-info-content"></div>
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
    function showModeratorInfo(moderatorId) {
        fetch(`/superuser/moderators/info/${moderatorId}`)
            .then(response => response.json())
            .then(data => {
                let content = `
                
                <p><strong>Moderator Picture:</strong></p>
                <img src="${data.moderatorPicture}" alt="Moderator Picture" class="img-fluid" style="max-width: 100%;">

                    <p><strong>Email:</strong> ${data.email}</p>
                    <p><strong>Name:</strong> ${data.name}</p>
                    <p><strong>Gender:</strong> ${data.gender}</p>
                    <p><strong>Date of Birth:</strong> ${data.dateOfBirth}</p>
                    <p><strong>Contact Number:</strong> ${data.contactNumber}</p>
                <p><strong>Certification:</strong> <a href="${data.certification}" target="_blank" class="btn btn-primary">View Certification</a></p>
                <p><strong>Identity Proof:</strong> <a href="${data.identityProof}" target="_blank" class="btn btn-primary">View Identity Proof</a></p>
                `;
                document.getElementById('moderator-info-content').innerHTML = content;
                var moderatorModal = new bootstrap.Modal(document.getElementById('moderator-info-modal'));
                moderatorModal.show();
            })
    }

    function setRejectUserId(userId) {
        document.getElementById('rejectUserId').value = userId;
        document.getElementById('rejectForm').action = '/superuser/reject_user/' + userId;
    }
</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
