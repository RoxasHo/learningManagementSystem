<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Home</title>
        <link rel="stylesheet" href="{{ asset('assets/css/PagesComponent.css')}}">
        <link rel="stylesheet" href="{{ asset('assets/css/EditPage.css')}}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        @livewireStyles
    </head>
    <body class="App">
    <header class="header">  
      <nav >
        <ul class="nav-links">
          
          
          <li><a href="#">Search</a></li>
          <li><a href="#">Forum</a></li>
          <li><a href="#">Subscription</a></li>
          <li><a href="#">Services</a></li>
          <li><a href="#">Login </s></li>
          <li><a href ="#">Profile</NavLink></li>
        </ul>
      </nav>
    </header>
    <aside class="sidebar">
      <ul>
        <nav>
        <li><a href="#">Search</a></li>
        <li><a href="{{ url('teacher-course',['id'=>1]) }}">Courses</a></li>
        <li><a href="#">Edit</a></li>
        <li><a href="#">Search</a></li>
        </nav>
      </ul>
    </aside>
      



      <div class="main-content" style="width:100%">
      <div class="container" >
     
      
      <div class="left" style="width:50%">
    <!-- Button to toggle the form visibility -->
    

    

    <br />

    <!-- List of chapters with content selection options -->
    <div>
        @foreach ($chapters as $chapter)
            <div style="margin-bottom: 20px;">
                <!-- Chapter selection and delete buttons -->
                <button
                    class="btn btn-primary"
                    onclick="toggleChapterContent({{ $chapter->id }})"
                >
                    Chapter {{ $chapter->chapter_number }}: {{ $chapter->chapter_name }}
                </button>
                
                
                @if($chapter->status == 'Complete')
                    <i class="bi bi-check-circle text-success"></i>
                @elseif($chapter->status == 'Locked')
                <i class="bi bi-lock-fill text-success"></i>
                @endif

                <!-- Content options (Material, Quiz) displayed when chapter is selected -->
                <div id="chapterContent{{ $chapter->id }}" style="display: none; margin-left: 20px;">
                    <ul style="list-style: none; padding-left: 0;">
                        @if($chapter->status != 'Locked')
                        <li>
                            <a  href="{{url('course-study',['student_id'=>$student_id,'course_id'=>$course_id,'chapter_id'=>$chapter->id,'selectedType'=>'Material'])}}" class="btn btn-secondary "> Course Content </a>
                        </li>
                        <li>
                        <a  href="{{url('course-study',['student_id'=>$student_id,'course_id'=>$course_id,'chapter_id'=>$chapter->id,'selectedType'=>'Quizz'])}}" class="btn btn-secondary "> Quizz </a>
                            
                        </li>
                        @endif
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
                                {!! $row->content !!}
                                {{ $content_id= $materials[0]->material_id }}
                            @endif
                        @endforeach
                        
                    @endif
                    @if($selectedType=='Quizz')
                        @foreach ($quizzs as $row)
                        @if($row->chapter_id == $chapter_id)
                          {{$row->content}}
                          {{$content_id = $row->quizz_id}}
                        
                        <table> 
                          
                          <tr><td><h3>Question {{$row->question_number}}</h3> {{$row->statement}}</td></tr>
                          <tr><td>Option 1 {{$row->option1}}</td></tr>
                          <tr><td>Option 2 {{$row->option2}}</td></tr>
                          <tr><td>Option 3 {{$row->option3}}</td></tr>
                          <tr><td>Option 4 {{$row->option4}}</td></tr>
                          <tr><td>Option 5 {{$row->option5}}</td></tr>
                          <tr><td>Option 6 {{$row->option6}}</td></tr>  
                          <tr><td>answer {{$row->answer}}</td></tr>
                        </table>
                        @endif    
                        @endforeach
                        
                    @endif
                </div>
            </div>
  </div>
    
       
    </div>
</div>
</div>

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
        </div>
        <footer class="footer">Footer</footer>
    </div> 
    </body>
</html>