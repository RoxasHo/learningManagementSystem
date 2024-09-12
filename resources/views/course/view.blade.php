<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
        <title>Home</title>
        <link rel="stylesheet" href="{{ asset('assets/css/PagesComponent.css')}}">
        <link rel="stylesheet" href="{{ asset('assets/css/EditPage.css')}}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <style>
          .main-content {
            margin: 0 auto; /* Center the content horizontally */
            display: flex; /* Use flexbox for better alignment */
            flex-direction: column; /* Stack children vertically */
            align-items: center; /* Center children horizontally */
            justify-content: center; /* Center children vertically */
            max-width: 800px; /* Set a max-width for content */
            padding: 20px; /* Add some padding */
            background-color: #f9f9f9; /* Light background color */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        
          }
          table {
            width: 100%; /* Full width */
            border-collapse: collapse; /* Remove space between cells */
        }

        table th, table td {
            padding: 10px; /* Padding inside cells */
            text-align: left; /* Align text to the left */
            border-bottom: 1px solid #ddd; /* Border between rows */
        }

        table th {
            background-color: #f2f2f2; /* Light background for header */
        }
        .invisible{
          visibility: hidden;
        }
        </style>
</head>
<div class="App">
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
        <li><a href="{{ url('teacher-course',['id'=>1]) }}">TeacherCourses</a></li>
        <li><a href="{{ url('student-course',['id'=>1]) }}">StudentCourses</a></li>
        <li><a href="#">Search</a></li>
        </nav>
      </ul>
    </aside>
<div class="main-content">
            
<table>
            <tr>
                <th>Course Name:</th>
                <td>{{ $course->CourseName }}</td>
            </tr>
            <tr>
                <th>Difficulty:</th>
                <td>{{ $course->Difficulty }}</td>
            </tr>
            <tr>
                <th>Description:</th>
                <td>{{ $course->Description }}</td>
            </tr>
            <tr>
                <th>Our Contributors:</th>
                <td>
                    <ul>
                        @foreach ($teachers as $row)
                            <li><a href="#">{{ $row }}</a></li>
                        @endforeach
                    </ul>
                </td>
            </tr>
      
            
            <tr>
              <td>
                
              <form method="post" action="{{ route('enroll-course') }}">
                @csrf
              <input name="student_id"type="text" class="invisible" value="1">
              <input name="course_id" type="text" class="invisible" value={{$course->id}}>
              <td colspan="2"><button class="btn btn-primary" type="submit">Enroll</button></td>
              </form>
      </td>
            </tr>
      </table>
        
  </div>
   </div>
</div>
      </div>

      



<footer class="footer">Footer</footer>






</body>
</html>
