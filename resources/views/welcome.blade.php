<x-layout> 
<link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}" >
<script src="{{ asset('js/main.js') }}"></script>  
<div id="main-content"class="main-content">
    
    <section class="image-container" id="image-container">
        <img class="fade-image" src="/images/header.png">
        <img class="image-bg" src="/images/image-bg.png">
        <div class="text-overlay">
            <h2>Welcome to SuperKianHo<br> Learning Application</h2>
            <p>Hand-picked Instructor and expertly-crafted courses, <br>designed for modern students.</p>
            <a href="{{ route('courses.index') }}">Browse Courses</a>
        </div>
    </section>

    <section class="collaborators" id="collaborators">
        <h1>We collaborate with 325+ leading universities and companies</h1>
        <li>
            <ul><a href="https://www.ibm.com/us-en"><img src="/images/ibm.png"></ul></a>
            <ul><a href="https://duke.edu/"><img src="/images/duke-3.png"></ul></a>
            <ul><a href="https://about.google/?utm_source=google-MY&utm_medium=referral&utm_campaign=hp-footer&fg=1"><img src="/images/google.png"></ul></a>
            <ul><a href="https://illinois.edu/"><img src="/images/illinois-3.png"></ul></a>
            <ul><a href="https://www.imperial.ac.uk/"><img src="/images/imperial.png"></ul></a>
            <ul><a href="https://umich.edu/"><img src="/images/umich.png"></ul></a>
            <ul><a href="https://www.upenn.edu/"><img src="/images/penn.png"></ul></a>
        </li>
    </section>

    @if(optional(auth()->user())->role === 'Student')
    <section id="suggested-courses" class="suggested-courses">
        <h1>Suggested Courses for You</h1>
        @if(Auth::check())
            <div class="course-container-wrapper">
                @if($suggestedCourses->isEmpty())
                    <p>No course suggestions available right now. Explore more courses!</p>
                @else
                    @foreach($suggestedCourses as $course)
                        <a href="{{ route('courses.show', $course->id) }}" class="course-link">
                            <div class = "course-container">
                                <div class = "course-container-header" style="background-color: {{ $course -> backgroundColor }};">
                                    <div class="course-header-image" style="background-image: url('{{ optional($course->categories->first())->image_url }}'); ">
                                    </div>
                                </div>
                                <div class="course-container-content">
                                    <h3>{{ $course->course_name }}</h3>
                                    <p class="difficulty">Difficulty: {{ $course->difficulty }}</p>
                                    <p class="enrollment">{{ $course->enrollmentCount }} Users Have Enrolled</p>
                                    <p class="creation-date">Created on: {{ $course->created_at }}</p>
                                    <div class="star-rating" data-rating="{{ $course->averageRating ?? 0 }}"></div>
                                
                                </div>
                                <hr>

                                <div class = "course-container-footer">
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
                @endif
                </div>
            @else
                <p>Please <a href="{{ route('login') }}">log in</a> to see your suggested courses.</p>
            @endif
        <hr>
    </section>
    @endif

    <section id="trending-courses" class="trending-courses">
        <h1>Hot Trending Courses</h1>
            <div class="course-container-wrapper">
                @foreach($trendingCourses as $course)
                <a href="{{ auth()->check() ? route('courses.show', $course->id) : 'javascript:void(0);' }}" 
                    class="course-link" 
                    onclick="handleCourseLinkClick(event, '{{ auth()->check() ? route('courses.show', $course->id) : route('login') }}')">
                        <div class = "course-container">
                            <div class = "course-container-header" style="background-color: {{ $course -> backgroundColor }};">
                                <div class="course-header-image" style="background-image: url('{{ optional($course->categories->first())->image_url }}'); ">
                                </div>
                            </div>
                            <div class="course-container-content">
                                <h3>{{ $course->course_name }}</h3>
                                <p class="difficulty">Difficulty: {{ $course->difficulty }}</p>
                                <p class="enrollment">{{ $course->enrollmentCount }} Users Have Enrolled</p>
                                <div class="star-rating" data-rating="{{ $course->averageRating ?? 0 }}"></div>
                            
                            </div>
                            <hr>

                            <div class = "course-container-footer">
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
        <hr>
    </section>

    <section id="most-rated-courses" class="most-rated-courses">
        <h1>Highest Rated Courses</h1>
            <div class="course-container-wrapper">
                @foreach($mostRatedCourses as $course)
                <a href="{{ auth()->check() ? route('courses.show', $course->id) : 'javascript:void(0);' }}" 
                    class="course-link" 
                    onclick="handleCourseLinkClick(event, '{{ auth()->check() ? route('courses.show', $course->id) : route('login') }}')">
                        <div class = "course-container">
                            <div class = "course-container-header" style="background-color: {{ $course -> backgroundColor }};">
                                <div class="course-header-image" style="background-image: url('{{ optional($course->categories->first())->image_url }}'); ">
                                </div>
                            </div>
                            <div class="course-container-content">
                                <h3>{{ $course->course_name }}</h3>
                                <p class="difficulty">Difficulty: {{ $course->difficulty }}</p>
                                <p class="enrollment">{{ $course->enrollmentCount }} Users Have Enrolled</p>
                                <div class="star-rating" data-rating="{{ $course->averageRating ?? 0 }}"></div>
                            
                            </div>
                            <hr>

                            <div class = "course-container-footer">
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
        <hr>
    </section>

    <section id="newly-created-courses" class="new-courses">
        <h1>Newly Created Courses</h1>
        <div class="course-container-wrapper">
                @foreach($newlyCreatedCourses as $course)
                <a href="{{ auth()->check() ? route('courses.show', $course->id) : 'javascript:void(0);' }}" 
                    class="course-link" 
                    onclick="handleCourseLinkClick(event, '{{ auth()->check() ? route('courses.show', $course->id) : route('login') }}')">
                        <div class = "course-container">
                            <div class = "course-container-header" style="background-color: {{ $course -> backgroundColor }};">
                                <div class="course-header-image" style="background-image: url('{{ optional($course->categories->first())->image_url }}'); ">
                                </div>
                            </div>
                            <div class="course-container-content">
                                <h3>{{ $course->course_name }}</h3>
                                <p class="difficulty">Difficulty: {{ $course->difficulty }}</p>
                                <p class="enrollment">{{ $course->enrollmentCount }} Users Have Enrolled</p>
                                <p class="creation-date">Created on: {{ $course->created_at }}</p>
                                <div class="star-rating" data-rating="{{ $course->averageRating ?? 0 }}"></div>
                            
                            </div>
                            <hr>

                            <div class = "course-container-footer">
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
        <hr>
    </section>
    
    @if(optional(auth()->user())->role === 'Student')
    <section id="enrolled-courses" class="enrolled-courses">
        <h1>Your Enrolled Courses</h1>
            <div class="course-container-wrapper">   
                @if ($enrolledCourses->isEmpty())
                    <p>You are not enrolled in any courses yet.</p>
                @else
                    @foreach($enrolledCourses as $course)
                        <a href="{{ route('courses.show', $course->id) }}" class="course-link">
                            <div class = "course-container">
                                <div class = "course-container-header" style="background-color: {{ $course -> backgroundColor }};">
                                    <div class="course-header-image" style="background-image: url('{{ optional($course->categories->first())->image_url }}'); ">
                                    </div>
                                </div>
                                <div class="course-container-content">
                                    <h3>{{ $course->course_name }}</h3>
                                    <p class="difficulty">Difficulty: {{ $course->difficulty }}</p>
                                    <p class="enrollment">{{ $course->enrollmentCount }} Users Have Enrolled</p>
                                    <div class="star-rating" data-rating="{{ $course->averageRating ?? 0 }}"></div>
                                
                                </div>
                                <hr>

                                <div class = "course-container-footer">
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
                @endif
        </div>
    </section>
    @endif

</div>
</x-layout>
<script>
    function handleCourseLinkClick(event, courseUrl) {
    if (!{{ auth()->check() ? 'true' : 'false' }}) {
        event.preventDefault(); // Prevent the default action (navigation)
        alert('You must log in to view this course.'); // Show alert message
        window.location.href = '{{ route('login') }}'; // Redirect to the login page
    } else {
        window.location.href = courseUrl; // Redirect to the course page
    }
}

</script>