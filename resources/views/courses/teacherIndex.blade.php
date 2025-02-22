<link
    href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/teacherIndex.css') }}" >
<x-layout>
<div class="main-content" id="main-content">
    <div class="container">
        <div class="row mb-3">
            <div class="col-6">
                <h5 class="mb-4">Course List</h5>
            </div>
            <div class="col-6 text-right">
                <!-- Relocated "Add Course" button -->
                <button id="add-course-button" class="btn btn-primary" onclick="toggleForm(true)">
                    + Add Course
                </button>
            </div>
            </div>    
            <p class="text-danger"></p>                 
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Sr.No</th>
                        <th scope="col">Course Title</th>
                        <th scope="col">Course Difficulty</th>
                        <th scope="col" width="200">Edit</th>
                        <th scope="col" width="200">Status</th>
                        <th scope="col" width="200">Role </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($course as $row)
                        <tr scope="row">
                        <td>{{$loop ->iteration}} </td>
                        
                        <td>{{$row->course_name}} </td>
                        <td>{{$row->difficulty}} </td>
                        
                        <td><a href="{{ url('edit-course',['course_id'=>$row->id,'chapter_id'=>'null','selectedType'=>'Material']) }}">Edit</a> </td>
                        <td>{{$row->status}}</td>
                        <td>{{$row->role}}</td>
                        </tr>
                    @endforeach                                                               
                </tbody>
            </table>  
        </div>
    </div>

    <div>
        <!-- Initially hide the form -->
        <form id="floating-form" class="floating-form" method="POST" action="{{ route('add-course') }}" style="display: none;">
            @csrf
            <label>Course Name: </label>
            <input type="hidden" name="teacher_id" value="{{$id}}">
            <input 
                class="CourseName" 
                type="text" 
                name="course_name" 
                placeholder="Enter course name" 
                required 
            />
            <label>Difficulty: </label>
            <select 
                class="Difficulty" 
                name="difficulty"
                required>
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Expert">Expert</option>
            </select>

            <br/>

            <label>Category:</label>
            <select name="category_id" class="form-control">
                @foreach($category as $row)
                    <option value="{{$row->id}}">{{ $row->name }}</option>
                @endforeach
            </select>
            <label>Brief Description: </label>
            <input 
                class="Description" 
                type="text" 
                name="course_description" 
                placeholder="Enter description (max: 248)" 
                required 
            />
            <button class="close-button" type="button" onclick="toggleForm(false)">
                Close
            </button>
            <button type="submit">Submit</button>
        </form>
    </div>
</div>
</x-layout>


<script>
    function toggleForm(isVisible) {
        const form = document.getElementById('floating-form');
        const button = document.getElementById('floating-button');

        if (isVisible) {
            form.style.display = 'block';
            button.style.display = 'none';
        } else {
            form.style.display = 'none';
            button.style.display = 'block';
        }
    }
    
</script> 


