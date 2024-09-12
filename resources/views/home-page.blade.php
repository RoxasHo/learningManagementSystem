<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Home</title>
        <link rel="stylesheet" href="{{ asset('assets/css/PagesComponent.css')}}">
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
        <li><a href="{{ url('teacher-course',['id'=>1]) }}">Teacher-Courses</a></li>
        <li><a href="{{ url('view-course',['id'=>1]) }}">Student-Courses</a></li>
        <li><a href="#">Search</a></li>
        </nav>
      </ul>
    </aside>




      <div class="main-content">
      
          
        </div>
        <footer class="footer">Footer</footer>
      



    </div>
    

    
    </body>
</html>
