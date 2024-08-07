<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1> Creating course</h1>
        
        <form method="post" action="{{url('add_new_course')}}">
            
            @csrf
            @method('POST')
  Course Name: <input type="text" name="CourseName">
  <br><br>
  Difficulty :<select name="Difficulty" id="Difficulty">
      <option value="Beginner">Beginner</option>
      <option value="Intermediate">Intermediate</option>
      <option value="Advanced">Advanced</option>
  </select>
  <br/><br/>
  
  Description: <textarea id="Description" name="Description" rows="5" cols="40"></textarea>
  <br><br>
  
  <button class="btn" type="submit" onclick="return confirm('Confirm adding?')">Add Course</button>  
  
</form>

    </body>
</html>
