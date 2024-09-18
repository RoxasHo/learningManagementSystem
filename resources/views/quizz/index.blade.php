<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
        <title>Home</title>
        <link rel="stylesheet" href="{{ asset('assets/css/PagesComponent.css')}}">
        <link rel="stylesheet" href="{{ asset('assets/css/EditPage.css')}}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- CSRF Token Setup for AJAX -->
        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Automatically add CSRF token to every AJAX request
                }
            });
        </script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
        .question-card {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
        }
        .question-header {
            background-color: #f8f9fa;
            padding: 10px;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
        }
        .question-body {
            padding: 10px;
        }
        .option-icon {
            margin-right: 10px;
        }
        .correct-answer {
            color: green;
        }
        .incorrect-answer {
            color: red;
        }
        .add-option-form, .add-answer-form{
            margin-top:20px;
        }
        .add-question-form {
            margin-top: 20px;
            display:none;
        }
        
        

    </style>
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
        <a>
        <li><a href="#">Search</a></li>
        <li><a href="{{ url('teacher-course',['id'=>1]) }}">Courses</NavLink></li>
        <li><a href ="#">Edit</a></li>
        <li><a href="#">Search</a></li>
        </nav>
      </ul>
    </aside>
<div class="main-content">
<div class="container mt-4">
    <!-- Form to Add New Question -->
    <div class="add-question-form">
        <h4>Add New Question</h4>
        <form method="POST" action="{{ route('add-question') }}">
            @csrf
            <div class="mb-3">
                <label for="new-question-statement" class="form-label">Question Statement</label>
                <input type="text" class="form-control" id="new-question-statement" name="statement" required>
                <label for="new-question-number" class="form-label">Question Number</label>
                <input type="text" class="form-control" id="new-question-number" name="question_number" required>
                
                
            </div>
            <input type="hidden" name="quizz_id" value="{{ $quizz_id }}"> <!-- Ensure you pass the quizz_id if needed -->
            <input type="hidden" name="type" value="Question">
            <button type="submit" class="btn btn-primary">Add Question</button>
        </form>
    </div>

    <!-- Button to Show Add Question Form -->
    <button class="btn btn-primary mb-4" onclick="toggleAddQuestionForm()">Add New Question</button>

    @foreach ($groupedQuizz as $questionNumber => $questions)
        <!-- Question Card -->
        <div class="question-card">
            <!-- Question Header -->
            <div class="question-header">
                <h5>Question {{ $questionNumber }}</h5>
            </div>
            <!-- Question Body -->
            <div class="question-body">
                @php
                    $mainQuestion = collect($questions)->firstWhere('type', 'Question');
                @endphp
                
                <!-- Display Main Question -->
                @if ($mainQuestion)
                    <div class="mb-3">
                        
                          <input type="hidden" id="question_id" name="question_id" value="{{$questions[0]->question_id}}">
                          <input type="hidden" id="question_number" name="question_number" value="{{$questionNumber}}">
                        <input type="text" class="form-control d-none" id="question-{{$mainQuestion->question_id}}-input" name="statement" value="{{ $mainQuestion->statement }}">
                        
                        <h6 id="question-{{$mainQuestion->question_id}}" class="d-inline" name="statement">{{ $mainQuestion->statement }}</h6>
                        <!-- Edit, Save, Cancel Buttons -->
                        <div class="edit-buttons mt-2">
                            <button class="btn btn-sm btn-primary" onclick="toggleEdit('question-{{$mainQuestion->question_id}}')">Edit</button>
                          
                            
                        
                            <button class="btn btn-sm btn-success d-none" id="save-question-{{$mainQuestion->question_id}}" onclick="saveChanges('question-{{$mainQuestion->question_id}}')">Save</button>
                            
                            <button class="btn btn-sm btn-secondary d-none" id="cancel-question-{{$mainQuestion->question_id}}" onclick="cancelEdit('question-{{$mainQuestion->question_id}}')">Cancel</button>
                            
                            <button class="btn btn-sm btn-danger" onclick="deleteItem('question-{{$mainQuestion->question_id}}', 'question')">Delete</button>
                            
                            
                            
                        </div>
                    </form>
                        <!-- Form to Add Option -->
                        <div class="add-option-form" id="add-option-form-{{$mainQuestion->question_id}}">
                            
                            <form method="POST" action="{{ route('add-option') }}">
                                @csrf
                                <input type="hidden" name="question_id" value="{{ $mainQuestion->question_id }}">
                                <div class="mb-3">
                                    <label for="option-statement-{{$mainQuestion->question_id}}" class="form-label">Option Statement</label>
                                    <input type="text" class="form-control" id="option-statement-{{$mainQuestion->question_id}}" name="statement" required>
                                </div>
                                <input type="hidden" name="question_number" value="{{$mainQuestion->question_number}}">
                                <input type="hidden" name="quizz_id" value="{{$quizz_id}}">
                                <button type="submit" class="btn btn-primary">Add Option</button>
                            </form>
                        </div>

                        <!-- Form to Add Answer -->
                        <div class="add-answer-form" id="add-answer-form-{{$mainQuestion->question_id}}">
                            <form method="POST" action="{{ route('add-answer') }}">
                                @csrf
                                <input type="hidden" name="question_id" value="{{ $mainQuestion->question_id }}">
                                <div class="mb-3">
                                    <label for="answer-statement-{{$mainQuestion->question_id}}" class="form-label">Answer Statement</label>
                                    <input type="text" class="form-control" id="answer-statement-{{$mainQuestion->question_id}}" name="statement" required>
                                </div>
                                <input type="hidden" name="question_number" value="{{$mainQuestion->question_number}}">
                                <input type="hidden" name="quizz_id" value="{{$quizz_id}}">
                                <button type="submit" class="btn btn-primary">Add Answer</button>
                            </form>
                        </div>
                    </div>
                @else
                    <h6 class="text-danger">No question found for this number.</h6>
                @endif

                <!-- Display Options and Answers -->
                <div class="mt-3">
                    @foreach ($questions as $question)
                        @if ($question->type === 'Option' || $question->type === 'Answer')
                            <div class="mb-2">
                                <input type="text" class="form-control d-none" id="option-{{$question->question_id}}-input" value="{{ $question->statement }}">
                                <div class="d-flex align-items-center">
                                    <i class="bi {{ $question->type === 'Answer' ? 'bi-check-circle correct-answer' : 'bi-square' }} option-icon"></i>
                                    <span id="option-{{$question->question_id}}">
                                        {{ $question->statement }}
                                    </span>
                                    <div class="edit-buttons ms-3">
                                        <button class="btn btn-sm btn-primary" onclick="toggleEdit('option-{{$question->question_id}}')">Edit</button>
                                        
                                        <button class="btn btn-sm btn-success d-none" id="save-option-{{$question->question_id}}" onclick="saveChanges('option-{{$question->question_id}}')">Save</button>
                                        
                                        
                                        
                                        
                                        <button class="btn btn-sm btn-secondary d-none" id="cancel-option-{{$question->question_id}}" onclick="cancelEdit('option-{{$question->question_id}}')">Cancel</button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteItem('option-{{$question->question_id}}', 'option')">Delete</button>
                                        
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function toggleAddQuestionForm() {
        const form = document.querySelector('.add-question-form');
        form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
    }
    function toggleEdit(elementId) {
        document.getElementById(elementId).classList.toggle('d-none');
        document.getElementById(elementId + '-input').classList.toggle('d-none');
        document.getElementById('save-' + elementId).classList.toggle('d-none');
        document.getElementById('cancel-' + elementId).classList.toggle('d-none');
    }

    function saveChanges(elementId) {
    // Get the edited input element and the span element
    const inputElement = document.getElementById(elementId + '-input');
    const spanElement = document.getElementById(elementId);

    // Update the span element's content with the new input value
    spanElement.textContent = inputElement.value;

    // Log the values for debugging
    console.log(elementId + ' inputElement: ' + inputElement + ' spanElement: ' + spanElement.innerText);

    // Prepare the data to send in the POST request
    const updatedStatement = spanElement.innerText;

    // Extract the questionId by splitting the elementId by the dash
    const questionId = elementId.split('-')[1]; // Extract the part after 'option-'
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // Send a POST request to update the question
    fetch('/update-question', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json', // Sending JSON data
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            question_id: questionId,
            statement: updatedStatement
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Success:', data);
        // Handle success response (e.g., display a success message)
    })
    .catch(error => {
        console.error('Error:', error);
        // Handle error response (e.g., display an error message)
    });

    // Switch out of edit mode
    toggleEdit(elementId);
}


    function cancelEdit(elementId) {
        toggleEdit(elementId);
    }

    function deleteQuestion(id) {
        

        // Here you can send an AJAX request to delete the data in the database
        document.getElementById(elementId).parentElement.remove();
    }

    function toggleType(elementId) {
        const currentType = document.getElementById(elementId).classList.contains('correct-answer') ? 'Answer' : 'Option';
        const newType = currentType === 'Answer' ? 'Option' : 'Answer';
        // Here you can send an AJAX request to update the type in the database
        document.getElementById(elementId).classList.toggle('correct-answer');
        document.getElementById('option-' + elementId).querySelector('i').classList.toggle('bi-check-circle');
        document.getElementById('option-' + elementId).querySelector('i').classList.toggle('bi-square');
        // Optionally, change button text to reflect the new type
        document.querySelector(`[onclick="toggleType('${elementId}')"]`).textContent = `Switch to ${newType}`;
    }

    function toggleAddOptionForm(question_id) {
        const form = document.getElementById(`add-option-form-${question_id}`);
    console.log(`Toggling form for question_id: ${question_id}, Found form: `, form); // Debugging line
    
    if (form) {
        form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
    } else {
        console.error(`Form with ID add-option-form-${question_id} not found.`);
    }
    }

    function toggleAddAnswerForm(question_id) {
        const form = document.getElementById(`add-answer-form-${question_id}`);
        form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
    }
</script>


</body>
</html>
