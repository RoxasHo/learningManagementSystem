<meta charset="UTF-8">
<link rel="stylesheet" href="{{ asset('css/teacherContent.css')}}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<x-layout>

    <div class="main-content" id="main-content">
    @if (\Session::has('success'))
            <div class="alert alert-success">
                <ul class="mb-0">
                    <li>{!! \Session::get('success') !!}</li>
                </ul>
            </div>
        @endif
        @if (\Session::has('failed'))
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <li>{!! \Session::get('failed') !!}</li>
                </ul>
            </div>
        @endif
        <div class="container" >
        
            <form action="{{ url('teacher-course', ['teacher_id' => $teacher->id]) }}" method="get">
                    <button type="submit" class="btn btn-primary" style="margin:10px 20px 10px 10px">Go to Teacher Course</button>
            </form>

            <form action="{{  url('view-course-team',['course_id'=>$course_id]) }}" method="get">
                <button type="submit" class="btn btn-primary" style="margin:10px 20px 10px 10px"> Manage Team</button>
            </form>

            <div class="left">
            <!-- Button to toggle the form visibility -->
            <button onclick="toggleFormVisibility()" class="btn btn-success">Add a Chapter</button>

            <!-- Form for adding a chapter -->
            <div id="addChapterForm" style="display: none; margin-top: 10px; background-color: #f9f9f9; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <form action="{{ url('add-chapter') }}" method="POST" style="display: flex; flex-direction: column; align-items: center;">
                @csrf
                <input type="hidden" name="course_id" value="{{ $course_id }}">
                <div style="margin-bottom: 10px; width: 100%;">
                    <label for="chapter_number">Chapter Number:</label>
                    <input type="text" id="chapter_number" name="chapter_number" class="form-control" required>
                </div>
                <div style="margin-bottom: 10px; width: 100%;">
                    <label for="chapter_name">Chapter Name:</label>
                    <input type="text" id="chapter_name" name="chapter_name" class="form-control" required>
                </div>
                <div style="margin-bottom: 10px; width: 100%;">
                    <label for="chapter_description">Description:</label>
                    <textarea id="chapter_description" name="chapter_description" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 10px 20px; font-size: 16px;">Submit</button>
            </form>
            </div>

            <br />

            <!-- List of chapters with content selection options -->
            <div>
            @foreach ($chapters as $chapter)
                <div style="margin-bottom: 20px;">
                    <!-- Chapter selection and delete buttons -->
                    <button class="btn btn-primary" onclick="toggleChapterContent({{ $chapter->id }})">
                        Chapter {{ $chapter->chapter_number }}: {{ $chapter->chapter_name }}
                    </button>
                    <a href="{{url('delete-chapter',['id'=>$chapter->id])}}" onclick="event.preventDefault(); 
                        if(confirm('Are you sure you want to delete this item?')) {
                            document.getElementById('delete-form-{{ $chapter->id }}').submit();
                        }" 
                        class="btn btn-danger">
                            Delete
                        </a>
                    <form method="post" id = 'delete-form-{{ $chapter->id }}' style="display:none" action="{{url('delete-chapter',['id'=>$chapter->id])}}">
                        @csrf
                        @method('DELETE')
                    <button class="btn btn-danger" type="submit">Delete</button>
                    </form>
                    <!-- Content options (Material, Quiz) displayed when chapter is selected -->
                    <div id="chapterContent{{ $chapter->id }}" style="display: none; margin-left: 20px;">
                        <ul style="list-style: none; padding-left: 0;">
                            <li>
                                <a  href="{{url('edit-course',['course_id'=>$course_id,'chapter_id'=>$chapter->id,'selectedType'=>'Material'])}}" class="btn btn-secondary "> Course Content </a>
                            </li>
                            <li>
                            <a  href="{{url('edit-course',['course_id'=>$course_id,'chapter_id'=>$chapter->id,'selectedType'=>'Quizz'])}}" class="btn btn-secondary "> Quizz </a>
                            </li>
                        </ul>
                    </div>
                </div>
            @endforeach
            </div>
        </div>

        <div class="right">
            <div className="content-container">
                    <div className="content-body">
                        @if($selectedType=='Material')
                            @foreach ($materials as $row)
                                @if($row->chapter_id == $chapter_id) 
                                   
                                    <p class="material-content">{!! $row->content !!}</p> <!-- Existing content -->
                                    <a href="{{url('edit-material',['id'=>$row->material_id])}}">Edit It</a>
                                @endif
                            @endforeach
                        @endif

                        @if($selectedType=='Quizz')
                            @foreach ($quizzs as $questionNumber => $items)
                                @if($questionNumber)
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
                                @endif
                            @endforeach
                            <a href="{{url('edit-quizz',['id'=>$quizz_id])}}">Edit it</a>
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
    </script>
    <script type="module">
import {
  ClassicEditor,
  Essentials,
  Bold,
  Italic,
  Font,
  Paragraph,
  Alignment,
  Heading,
  Link,
  List,
  Image,
  ImageToolbar,
  ImageCaption,
  ImageStyle,
  Base64UploadAdapter,
  BlockQuote,
  Table,
  TableToolbar,
} from 'ckeditor5';

document.addEventListener('DOMContentLoaded', () => {
  // Get the content from the <p> element
  const content = document.querySelector('.material-content').innerHTML;

  // Set the content of the hidden textarea
  const editorElement = document.querySelector('#editor');
  editorElement.value = content;

  ClassicEditor
    .create(editorElement, {
      readOnly: true,  // Set readOnly mode
      image: {
        toolbar: ['toggleImageCaption', 'imageTextAlternative', 'ckboxImageEdit']
      },
      plugins: [
        Essentials, Bold, Italic, Font, Paragraph, Alignment, Heading,
        Link, List, Image, ImageToolbar, ImageCaption, ImageStyle, Base64UploadAdapter,
        BlockQuote, Table, TableToolbar
      ],
      toolbar: {
        items: [
          'heading', '|',
          'bold', 'italic', 'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
          'alignment', 'link', '|',
          'bulletedList', 'numberedList', '|',
          'imageUpload', 'blockQuote', 'insertTable', '|',
          'undo', 'redo'
        ]
      },
      table: {
        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
      }
    })
    .then(editor => {
      console.log('Editor initialized in read-only mode.');
    })
    .catch(error => {
      console.error('Error initializing editor:', error);
    });
});
</script>
