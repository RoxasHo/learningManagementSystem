<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="{{ asset('css/teacherContent.css') }}" >
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script src="{{ asset('js/show.js') }}"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<x-layout>
<div class="main-content" id="main-content">
    <br/><br/>
    <a class="btn btn-primary" href="{{url('view-course-team',['course_id'=>$course_id])}}">View Course Team</a>
    <div class="container">
        <div class="left">
            <br />
            <!-- Button to toggle the form visibility -->
            

            <!-- List of Chapters -->
            <div class="chapter-list">
                @foreach ($chapters as $chapter)
                <div class="chapter-item">
                    <!-- Chapter selection and delete buttons -->
                    <button class="btn btn-primary chapter-button" onclick="toggleChapterContent({{ $chapter->id }})">
                        <span class="chapter-text">Chapter {{ $chapter->chapter_number }}: {{ $chapter->chapter_name }}</span>
                        <span class="chapter-icons">
                            <i class="bi bi-caret-down-fill"></i>
                        </span>
                    </button>
                     
                    
                    <button onclick="deleteChapter('{{ $chapter->id }}')" class="btn btn-danger delete-btn">
                        Delete
                    </button>

                    <!-- Content options (Material, Quiz) displayed when chapter is selected -->
                    <div id="chapterContent{{ $chapter->id }}" class="chapter-content dropdown" style="display: none;">
                        <ul class="dropdown-list">
                            <li><a href="{{ url('show-content', ['course_id'=>$course_id, 'chapter_id'=>$chapter->id, 'selectedType'=>'Material']) }}" class="btn btn-secondary">Course Content</a></li>
                            <li><a href="{{ url('show-content', ['course_id'=>$course_id, 'chapter_id'=>$chapter->id, 'selectedType'=>'Quizz']) }}" class="btn btn-secondary">Quiz</a></li>
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Right Content Area -->
        <div class="right">
            <div class="content-container">
                <div class="content-body">
                    @if($selectedType == 'Material')
                        @foreach ($materials as $row)
                            @if($row->chapter_id == $chapter_id)
                                {!! $row->content !!}
                                
                            @endif
                        @endforeach
                    @endif

                    @if($selectedType == 'Quizz')
                        @foreach ($quizzs as $question_number => $items)
                        <div class="question-group">
                            <h3>Question #{{ $question_number }}</h3>
                            @foreach ($items['questions'] as $question)
                            <div class="question">
                                <p><strong>{{ $question->statement }}</strong></p>
                            </div>
                            @endforeach

                            @foreach ($items['options'] as $option)
                            <div class="option">
                                <p>{{ $option->statement }}</p>
                            </div>
                            @endforeach

                            @foreach ($items['answers'] as $answer)
                            <div class="answer">
                                <p>{{ $answer->statement }}</p>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                        
                    @endif
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
    

</script>
        
   