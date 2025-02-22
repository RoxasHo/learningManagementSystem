<link rel="stylesheet" type="text/css" href="{{ asset('css/course.css') }}" >
<script src="{{ asset('js/course.js') }}"></script>  
<x-layout>

    <div id="main-content"class="main-content">

        <section class="view-courses">
            <div class="courses-list-wrapper">
            <div class="header-wrapper">
                <h1>Courses List</h1>
                <div class="filter-search-sort-wrapper">
                    <!-- Search Bar -->
                    <div class="search-wrapper">
                        <form id="search-form">
                            <div class="search-container">
                                <input type="text" id="search-input" class="form-control search-input" placeholder="Search for courses...">
                                <button type="submit" class="search-button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Filters & Sorting -->
                    <form id="filter-form" method="GET" action="{{ route('courses.index') }}" class="filter-sort-form">
                        <!-- Category Filter -->
                        <div class="filter-container">
                            <select name="category_id" class="form-control" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $selectedCategoryId == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sorting Dropdown -->
                        <div class="sort-container">
                            <select name="sort" class="form-control" onchange="this.form.submit()">
                                <option value="created_at" {{ $sortBy == 'created_at' ? 'selected' : '' }}>Sort By New</option>
                                <option value="course_name" {{ $sortBy == 'course_name' ? 'selected' : '' }}>Sort By Name</option>
                                <option value="rating" {{ $sortBy == 'rating' ? 'selected' : '' }}>Sort By Rating</option>
                                <option value="difficulty" {{ $sortBy == 'difficulty' ? 'selected' : '' }}>Sort By Difficulty</option>
                            
                            </select>

                            <select name="direction" class="form-control" onchange="this.form.submit()">
                                <option value="asc" {{ $sortDirection == 'asc' ? 'selected' : '' }}>Ascending</option>
                                <option value="desc" {{ $sortDirection == 'desc' ? 'selected' : '' }}>Descending</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <div class="course-container-wrapper">
                @foreach($courses as $course)
                <a href="{{ auth()->check() ? route('courses.show', $course->id) : 'javascript:void(0);' }}" 
                    class="course-link" 
                    onclick="handleCourseLinkClick(event, '{{ auth()->check() ? route('courses.show', $course->id) : route('login') }}')">
                        <div class="course-container" data-course-id="{{ $course->id }}" data-category-ids="{{ implode(',', $course->categories->pluck('id')->toArray()) }}">
                            <div class="course-container-header" style="background-color: {{ $course->backgroundColor }};">
                                <div class="course-header-image" style="background-image: url('{{ optional($course->categories->first())->image_url }}');">
                                </div>
                            </div>

                            <div class="course-container-content">
                                <h3>{{ $course->course_name }}</h3>
                                <p class="difficulty">Difficulty: {{ $course->difficulty }}</p>
                                <p class="enrollment">{{ $course->enrollmentCount }} Users Have Enrolled</p>
                                <div class="star-rating" data-rating="{{ $course->averageRating }}"></div>
                                @if($course->isEnrolled)
                                    <p class="enrolled-status">You are enrolled in this course</p>
                                @endif
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
        </div>
    </section>
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
