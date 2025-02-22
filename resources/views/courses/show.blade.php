<link rel="stylesheet" type="text/css" href="{{ asset('css/coursedetails.css') }}">
<script src="{{ asset('js/show.js') }}"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<x-layout>
    <div id="main-content" class="main-content">
        <section class="course-details-section" id="course-details-section">
            <div class="course-header">
                <div class="course-image" style="background-image: url('{{ optional($course->categories->first())->image_url }}');"></div>
                <div class="course-header-info">
                    <h1>{{ $course->course_name }}</h1>
                    <p>Language: {{ optional($course->categories->first())->name }}</p>
                    <p>Difficulty: {{ $course->difficulty }}</p>
                    <p>Rating: <div class="star-rating" data-rating="{{ $averageRating }}"></div></p>
                    <p>Time Created: {{ $createdAtString }}</p>
                    <p>Enrollments: {{ $enrollmentCount }} Users Have Enrolled</p>

                    <div class="button-wrapper">
                        @if($enrolled)
                            <form action="{{ url('course-study', ['student_id' => $student_id, 'course_id' => $course->id, 'chapter_id' => 'null', 'selectedType' => 'Material']) }}">
                                <button type="submit" class="btn btn-secondary custom-button">View Course</button>
                            </form>
                        @elseif($isTeacherOrModerator)
                            <form action="{{ url('course-study', ['student_id' => $student_id, 'course_id' => $course->id, 'chapter_id' => 'null', 'selectedType' => 'Material']) }}">
                                <button type="submit" class="btn btn-secondary custom-button">View Course</button>
                            </form>
                        @else
                            <form action="{{ route('enroll-course') }}" method="POST">
                                @csrf
                                <input type="hidden" name="course_id" value="{{ $course->id }}">
                                <button type="submit" class="btn btn-secondary custom-button">Enroll</button>
                            </form>
                        @endif

                        <!-- Rate Course Button -->
                        <form action="#" method="POST" id="rateCourseForm">
                            <button type="button" class="btn btn-secondary custom-button" id="rateCourseButton" data-course-id="{{ $course->id }}">Rate Course</button>
                        </form>
                    </div>
                </div>

                <div class="tutor-section">
                    @if($course->teachers->isNotEmpty())
                        @foreach($course->teachers as $teacher)
                            <div class="tutor-info">
                                <p>Taught by:</p>
                                <img src="{{ asset($teacher->teacherPicture) }}" alt="{{ $teacher->name }}" class="tutor-image">
                                <p class="tutor-name">{{ $teacher->name }}</p>
                            </div>
                        @endforeach
                    @else
                        <p>No tutors available for this course.</p>
                    @endif
                </div>
            </div>

            <div class="course-description">
                <h2>About This Course</h2>
                <p>{{ $course->course_description }}</p>
            </div>
        </section>

        <!-- Rating Modal -->
        <div id="ratingModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Rate this Course</h2>
                <div class="star-rating" id="starRating">
                    <span data-value="1">&#9733;</span>
                    <span data-value="2">&#9733;</span>
                    <span data-value="3">&#9733;</span>
                    <span data-value="4">&#9733;</span>
                    <span data-value="5">&#9733;</span>
                </div>
                <button id="submitRating" class="btn btn-primary">Submit Rating</button>
            </div>
        </div>
    </div>
</x-layout>
