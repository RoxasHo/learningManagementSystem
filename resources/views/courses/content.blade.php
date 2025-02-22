<script src="{{ asset('js/show.js') }}"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('css/content.css') }}" >
<meta name="csrf-token" content="{{ csrf_token() }}">
<x-layout>
    <div class="main-content" id="main-content">
        <section class="course-details-section" id="course-details-section">
            <div class="course-header">
                <div class="course-image" style="background-image: url('{{ optional($course->categories->first())->image_url }}');"></div>
                <div class="course-header-info">
                    <h1>{{ $course->course_name }}</h1>
                    <p>Language: {{ optional($course->categories->first())->name }}</p>
                    <p>Difficulty: {{ $course->difficulty }}</p>
                    <p>Rating: <div class="star-rating" data-rating="{{ $course->rating }}"></div></p>
                    <p>Created: {{ $createdAtString }}</p>
                    <p>Enrollments: {{ $enrollmentCount }} Users Have Enrolled</p>
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
        </section>
    
        <div class="container">
            <div class="left">
                <br />
                <h3 class="section-title">Chapters</h3>
                <!-- List of chapters with content selection options -->
                <div class="chapters-list">
                    @foreach ($chapters as $chapter)
                        <div class="chapter-item">
                            <button class="btn btn-primary chapter-button" onclick="toggleChapterContent({{ $chapter->id }})">
                                <span class="chapter-text">Chapter {{ $chapter->chapter_number }}: {{ $chapter->chapter_name }}</span>
                                
                                @if(auth()->user()->role === 'Student')
                                    <!-- Flex container for the icons on the right -->
                                    <span class="chapter-icons">
                                        @if($chapter->status == 'Complete')
                                            <i class="bi bi-check-circle text-success chapter-status"></i>
                                        @elseif($chapter->status == 'Locked')
                                            <button class="lock-button" onclick="unlockChapter({{  $chapter->id}}, {{$student_id }})">
                                                <i class="bi bi-lock-fill text-danger"></i>
                                            </button>
                                        @endif
                                        <i class="bi bi-caret-down-fill"></i>
                                    </span>
                                @endif
                            </button>

                            <div id="chapterContent{{ $chapter->id }}" class="chapter-content dropdown" style="display: none;">
                                <ul class="dropdown-list">
                                    @if(auth()->user()->role === 'Student')
                                        @if($chapter->status != 'Locked')
                                            <li>
                                                <a href="{{url('course-study',['student_id'=>$student_id,'course_id'=>$course_id,'chapter_id'=>$chapter->id,'selectedType'=>'Material'])}}" class="btn btn-secondary">Course Content</a>
                                            </li>
                                            <li>
                                                <a href="{{url('course-study',['student_id'=>$student_id,'course_id'=>$course_id,'chapter_id'=>$chapter->id,'selectedType'=>'Quizz'])}}" class="btn btn-secondary">Quiz</a>
                                            </li>
                                        @endif
                                    @else
                                        <li>
                                            <a href="{{url('course-study',['course_id'=>$course_id,'chapter_id'=>$chapter->id,'selectedType'=>'Material'])}}" class="btn btn-secondary">Course Content</a>
                                        </li>
                                        <li>
                                            <a href="{{url('course-study',['course_id'=>$course_id,'chapter_id'=>$chapter->id,'selectedType'=>'Quizz'])}}" class="btn btn-secondary">Quiz</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
    
            <div class="right">
                <div class="content-container">
                    <div class="content-body">
                        @if($selectedType=='Material')
                            @foreach ($materials as $row)
                                @if($row->chapter_id == $chapter_id) 
                                    <div class="material-content">{!! $row->content !!}</div>
                                    {{ $content_id= $materials[0]->material_id }}
                                @endif
                            @endforeach
                        @endif
                        @if($selectedType=='Quizz')
                            <form action="{{ route('submit-quizz') }}" method="POST">
                                @csrf
                                <input type="hidden" name="quizz_id" value="{{$quizz_id}}">
                                <input type="hidden" name="student_id" value="{{ $student_id }}">
                                @foreach ($quizzs as $questionNumber => $items)
                                    <div class="question-group">
                                        <h5>Question #{{ $questionNumber }}</h5>
                                        
                                        <!-- Display questions -->
                                        @foreach ($items['questions'] as $question)
                                            <div class="question">
                                                <p><strong>{{ $question->statement }}</strong></p>
                                            </div>
                                        @endforeach
                                        @foreach (array_merge($items['options'], $items['answers']) as $choice)
                                            <div class="form-check">
                                                <input type="checkbox" name="answers[{{ $questionNumber }}][]" id="choice-{{ $choice->quizz_id }}" value="{{ $choice->statement }}" class="form-check-input">
                                                <label class="form-check-label" for="choice-{{ $choice->quizz_id }}">{{ $choice->statement }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                                <button type="submit" class="btn btn-primary">Submit Answers</button>
                            </form>
                        @endif
                        <script>
                            @if (session('quizResult'))
                                alert('{{ session('quizResult') }}');
                            @endif
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
      

<script>
    
    function updateRightComponent() {
        // Get the value from the left component input field
        const inputValue = document.getElementById('left-input').value;
        
        // Update the right component output field
        document.getElementById('right-output').textContent = inputValue;
    }
    // Toggle the visibility of the add chapter form
    function toggleFormVisibility() {
        var form = document.getElementById('addChapterForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }

    // Toggle the visibility of the content options for the selected chapter
    function toggleChapterContent(chapterId) {
        // Hide all chapter content sections
        var allContentDivs = document.querySelectorAll('[id^="chapterContent"]');
        allContentDivs.forEach(function(div) {
            div.style.display = 'none';
        });

        // Show the selected chapter content
        var contentDiv = document.getElementById('chapterContent' + chapterId);
        contentDiv.style.display = 'block';
    }
    async function unlockChapter(chapter_id,student_id){
        console.log('chapter_id is '+chapter_id , 'student_id is' +student_id);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if (confirm("You want to use 1 point to unlock this chapter?") == true) {
            // Data to send to the backend
            const data = {
                chapter_id: chapter_id,
                student_id: student_id,
                // Add other data fields if needed
            };
            try {
                const config = {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken // Add the CSRF token to the headers
                    },
                    body: JSON.stringify(data)  // Send data with chapter_id and student_id
                };
                const response = await fetch('/unlock-chapter', config);
                if (response.ok) {
                    const json = await response.json();  // Parse the response JSON
                    location.reload();
                    return json;
                } else {
                    const errorData = await response.json();
                    console.error('Error:', errorData.message || 'Something went wrong.');
                    throw new Error(errorData.message || 'Failed to unlock chapter.');
                    
                }
            } catch (error) {
                console.error('Fetch error:', error);
                alert('An error occurred while unlocking the chapter. Please try again later.');
            }
        }
    }
</script>
      
   
