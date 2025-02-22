<href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp"
rel="stylesheet"
/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('css/teacherContent.css') }}" >
<x-layout>
<div class="main-content" style="width:100%" id="main-content">
    <br/><br/>

    <a class="btn btn-primary" href="{{url('view-course-team',['course_id'=>$course_id])}}">View Course Team</a>
        <div class="container" >
            <div class="left" style="width:50%">
                <br />
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
                            <button
                                class="btn btn-primary"
                                onclick="toggleChapterContent({{ $chapter->id }})"
                            >
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
                                    {!! $row->content !!}
                                    
                                    <a href="{{url('edit-material',['id'=>$row->material_id])}}">Edit It</a>
                                @endif
                            @endforeach 
                        @endif
                        @if($selectedType=='Quizz')
                            @foreach ($quizzs as $row)
                            @if($row->chapter_id == $chapter_id)
                            {!! $row->question_number !!}
                            {!! $row->statement !!}
                            <a href="{{url('edit-quizz',['id'=>$row->quizz_id])}}">Edit It</a>
                            @endif    
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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                //Initialize variables for side bar
                const sidebar = document.getElementById('sidebar');
                const toggleSidebarBtn = document.getElementById('toggleSidebar');
                const menuIcon = document.getElementById('menuIcon');
                const mainContent = document.getElementById('main-content');

                // Initialize sidebar and main content with expanded class
                sidebar.classList.add('expanded');
                mainContent.classList.add('expanded');
            
                //Menu icon logic
                menuIcon.addEventListener('click', function () {
                    if (sidebar.classList.contains('minimized')) {
                        sidebar.classList.remove('minimized');
                        sidebar.classList.add('expanded');
                        mainContent.classList.remove('minimized');
                        mainContent.classList.add('expanded');
                    } else {
                        sidebar.classList.add('minimized');
                        sidebar.classList.remove('expanded');
                        mainContent.classList.add('minimized');
                        mainContent.classList.remove('expanded');
                    }
                });

                //Side bar logic
                toggleSidebarBtn.addEventListener('click', function () {
                    if (sidebar.classList.contains('minimized')) {
                        sidebar.classList.remove('minimized');
                        sidebar.classList.add('expanded');
                        mainContent.classList.remove('minimized');
                        mainContent.classList.add('expanded');
                    } else {
                        sidebar.classList.add('minimized');
                        sidebar.classList.remove('expanded');
                        mainContent.classList.add('minimized');
                        mainContent.classList.remove('expanded');
                    }
                });

                // Close sidebar when clicking outside of it
                document.addEventListener('click', function (event) {
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickOnMenuIcon = menuIcon.contains(event.target);

                    if (!isClickInsideSidebar && !isClickOnMenuIcon) {
                        sidebar.classList.add('minimized');
                        sidebar.classList.remove('expanded');
                        mainContent.classList.add('minimized');
                        mainContent.classList.remove('expanded');
                    }
                });
            });
        </script>
        <footer class="footer">Footer</footer>

