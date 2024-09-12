<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
        <title>Home</title>
        <link rel="stylesheet" href="{{ asset('assets/css/PagesComponent.css')}}">
        <link rel="stylesheet" href="{{ asset('assets/css/EditPage.css')}}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

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
        <li><a href="{{ url('teacher-course',['id'=>1]) }}">Courses</a></li>
        <li><NavLink to ="/CourseEditPage">Edit</NavLink></li>
        <li><a href="#">Search</a></li>
        </nav>
      </ul>
    </aside>
<div class="main-content">
<div class="container container_overflow">
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-4">Course List</h5> 
                        <p class="text-danger"> </p>                 
                                <table class="table table-bordered">
                                <thead>
                                <tr>
                                <th scope="col">Sr.No</th>
                                <th scope="col">Course Title</th>
                                <th scope="col">Course Difficulty</th>
                                <th scope="col">Course Description</th>
                                <th scope="col" width="200">Edit</th>
                                <th scope="col" width="200">Status</th>
                                <th scope="col" width="200">Role </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($course as $row)

                                <tr scope="row">
                                <td>{{$loop ->iteration}} </td>
                                
                                <td>{{$row->CourseName}} </td>
                                <td>{{$row->Difficulty}} </td>
                                <td>{{$row->Description}} </td>
                                
                                <td><a href="{{ url('edit-course',['course_id'=>$row->id,'chapter_id'=>'null','selectedType'=>'Material']) }}">Edit</a> </td>
                                <td>{{$row->status}}</td>
                                <td>{{$row->role}}</td>
                                </tr>
                                @endforeach                                                               
                                </tbody>
                                </table>  
                    </div>
                </div>
            </div>
</div>

<div class ="container">
<x-FloatingButton/>




  </div>
      



<footer class="footer">Footer</footer>






</body>
</html>