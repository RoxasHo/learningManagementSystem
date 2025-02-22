<x-layout>
<link rel="stylesheet" type="text/css" href="{{ asset('css/profile.css') }}" >  
<script src="{{ asset('js/course.js') }}"></script>  
<div id="main-content"class="main-content">
    <section>
        <div class="profile-wrapper">
            <div class="profile-container">
                <div class="profile-header">
                    @if(session('success'))
                        <div class="alert alert-success custom-alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Display Error Message -->
                    @if(session('error'))
                        <div class="alert alert-danger custom-alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Profile Picture and Edit Button -->
                    <form action="{{ route('profile.updateStudentPicture') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="studentPicture" class="profile-picture-label">
                            <img src="{{ $student->studentPicture ? asset('storage/' . $student->studentPicture) : asset('images/default-profile.png') }}" alt="Student Picture" class="profile-picture">
                            <input type="file" id="studentPicture" name="studentPicture" accept="image/*" class="profile-picture-input">
                            <span class="edit-icon">&#9998;</span>
                        </label>
                        <div class="button-container">
                            <button type="submit" class="save-picture-button">Save Picture</button>
                        </div>
                    </form>

                    <!-- Profile Info -->
                    <div class="profile-info">
                        <h2>{{ $student->name }}</h2>
                        <p>Email: {{ $student->user->email }}</p>
                        <p>Points: {{ $student->points ?? 'No points available.' }}</p>
                        <div class="button-container">
                            <a href="{{ route('profile.editStudent', $student->user->email) }}" class="btn btn-primary">Edit Profile</a>
                        </div> 
                        <div class="button-container">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right container for profile content -->
            <div class="profile-content">
                <div class="profile-section">
                    <h3>Progress</h3>
                    <section id="courses-progress" class="courses-progress">
                        <h1>Continue from where you left off:</h1>
                        <div class="course-container-wrapper">
                            @foreach($coursesWithProgress as $courseData)
                                @php $course = $courseData['course']; @endphp
                                <a href="{{ route('courses.show', $course->id) }}" class="course-link">
                                    <div class="course-container">
                                        <div class="course-container-header" style="background-color: {{ $course->backgroundColor }};">
                                            <div class="course-header-image" style="background-image: url('{{ optional($course->categories->first())->image_url }}');">
                                            </div>
                                        </div>
                                        <div class="course-container-content">
                                            <h3>{{ $course->course_name }}</h3>
                                            <p class="difficulty">Difficulty: {{ $course->difficulty }}</p>
                                            <p>Completion: {{ $courseData['completion_percentage'] }}%</p>
                                            <div class="star-rating" data-rating="{{ $course->rating }}"></div>
                                        </div>
                                        <hr>
                                        <div class="course-container-footer">
                                            @foreach($course->teachers as $teacher)
                                                <div class="teacher-info">
                                                    <img src="{{ asset($teacher->teacherPicture) }}" alt="{{ $teacher->name }}" class="teacher-image">
                                                    <p class="teacher">{{ $teacher->name }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>
</div>
</x-layout>
