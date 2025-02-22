<x-layout>
<link rel="stylesheet" type="text/css" href="{{ asset('css/profile.css') }}" >  
<script src="{{ asset('js/course.js') }}"></script>  
<div id="main-content"class="main-content">
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

                    <form action="{{ route('profile.updateTeacherPicture') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="teacherPicture" class="profile-picture-label">
                            <img src="{{ $teacher->teacherPicture ? asset($teacher->teacherPicture) : asset('images/default-profile.png') }}" alt="Teacher Picture" class="profile-picture">
                            <input type="file" id="teacherPicture" name="teacherPicture" accept="image/*" class="profile-picture-input">
                            <span class="edit-icon">&#9998;</span>
                        </label>
                        <div class="button-container">
                            <button type="submit" class="save-picture-button">Save Picture</button>
                        </div>
                    </form>

                    <div class="profile-info">
                        <h2>{{ $teacher->user->name }}</h2>
                        <p>Email: {{ $teacher->user->email }}</p>
                        <div class="button-container">
                            <a href="{{ route('profile.editTeacher', $teacher->user->email) }}" class="btn btn-primary">Edit Profile</a>
                        </div>
                        <div class="button-container">
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="profile-content">
                <div class="profile-section">
                    <h3>Certification</h3>
                    <section id="courses-progress" class="courses-progress">
                        @if ($teacher->certification)
                            <a href="{{ asset($teacher->certification) }}" target="_blank">Download Certification</a>
                        @else
                            <p>No certification available.</p>
                        @endif
                    </section>
                </div>

                <div class="profile-section">
                    <h3>Identity Proof</h3>
                    <section id="courses-progress" class="courses-progress">
                        @if ($teacher->identityProof)
                            <a href="{{ asset($teacher->identityProof) }}" target="_blank">Download Identity Proof</a>
                        @else
                            <p>No identity proof available.</p>
                        @endif
                    </section>
                </div>
                
                <div class="profile-section">
                    <h3>Courses Created</h3>
                    <section id="courses-progress" class="courses-progress">
                        @if($teacher->courses->isEmpty())
                            <p>No courses created yet.</p>
                            <form action="{{ url('teacher-course', ['teacher_id' => $teacher->id]) }}" method="get">
                                <button type="submit" class="btn btn-primary">Create Course</button>
                            </form>
                        @else
                            <form action="{{ url('teacher-course', ['teacher_id' => $teacher->id]) }}" method="get">
                                <button type="submit" class="btn btn-primary">View Courses</button>
                            </form>
                        @endif
                    </section>
                </div>

            </div>
        </div>
    </section>
</div>
</x-layout>
