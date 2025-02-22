<x-layout>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/profile.css') }}">
    <script src="{{ asset('js/course.js') }}"></script>
    <div id="main-content" class="main-content">
        <section>
            <div class="profile-wrapper">
                <div class="profile-container">
                    <div class="profile-header">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('profile.updateModeratorPicture') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <label for="moderatorPicture" class="profile-picture-label">
                                <img src="{{ $moderator->moderatorPicture ? asset($moderator->moderatorPicture) : asset('images/default-profile.png') }}"
                                    alt="Moderator Picture" class="profile-picture">
                                <input type="file" id="moderatorPicture" name="moderatorPicture" accept="image/*"
                                    class="profile-picture-input">
                                <span class="edit-icon">&#9998;</span>
                            </label>
                            <button type="submit" class="save-picture-button">Save Picture</button>
                        </form>

                        <div class="profile-info">
                            <h2>{{ $moderator->name }}</h2>
                            <p>Moderator ID: {{ $moderator->moderatorID }}</p>
                            <p>Email: {{ $moderator->user->email }}</p>
                            <a href="{{ route('profile.editModerator', $moderator->user->email) }}"
                                class="btn btn-primary">Edit Profile</a>
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="profile-content">
                    <div class="profile-section">
                        <h3>Certification</h3>
                        @if ($moderator->certification)
                            <a href="{{ asset($moderator->certification) }}" target="_blank">Download Certification</a>
                        @else
                            <p>No certification available.</p>
                        @endif
                    </div>
                    <div class="profile-section">
                        <h3>Identity Proof</h3>
                        @if ($moderator->identityProof)
                            <a href="{{ asset($moderator->identityProof) }}" target="_blank">Download Identity Proof</a>
                        @else
                            <p>No identity proof available.</p>
                        @endif
                    </div>
                </div>

            </div>
            <div class="profile-wrapper2">
                <div class="profile-content">
                    <div class="profile-section">
                        <h3>Approve Courses</h3>

                        @if ($pendingCourses->isEmpty())
                            <p>No courses available for approval.</p>
                        @else
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Course Name</th>
                                        <th>Teacher</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingCourses as $course)
                                        <tr>
                                            <td>{{ $course->course_name }}</td>
                                            <td>{{ $course->teacher_name }}</td>
                                            <td>{{ ucfirst($course->status) }}</td>
                                            <td>
                                                <!-- Approve and Reject buttons -->
                                                <form action="{{ route('courses.approve', $course->id) }}" method="POST"
                                                    style="display: inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">Approve</button>
                                                </form>
                                                <form action="{{ route('courses.reject', $course->id) }}" method="POST"
                                                    style="display: inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">Reject</button>
                                                </form>
                                                <!-- View Course button -->
                                                <a href="{{ route('showContent', ['course_id' => $course->id, 'chapter_id' => 'null', 'selectedType' => 'null']) }}"
                                                    class="btn btn-primary">View Course</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
            <div class="profile-wrapper2">
                <div class="profile-content">
                    <div class="profile-section">
                        <h1>Manage Users</h1>
                        <table class="table table-striped">
                        <tbody>
    <tr>
        <td>
            <a href="{{ route('profile.students', ['email' => $moderator->user->email]) }}"
                class="list-group-item list-group-item-action">
                Students
            </a>
        </td>
    </tr>
    <tr>
        <td>
            <a href="{{ route('profile.teachers.active', ['email' => $moderator->user->email]) }}"
                class="list-group-item list-group-item-action">
                Teachers
            </a>
        </td>
    </tr>
</tbody>

                        </table>
                    </div>
                    </br>
                </div>
            </div>
            
    </div>
    </section>

</x-layout>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>