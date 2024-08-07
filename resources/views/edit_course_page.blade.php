<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Edit Course</title>
    </head>
    <body>
        <h1>Now Editing the course</h1>
        
        <div >
            <div style='display:inline-block;border: 5px solid red;'>
            <ul>
                <li><div class='materials' >ch1</div></li><!-- comment -->
                <li><div class='materials' >ch2</div></li><!-- comment -->
                <li><div class='materials' >ch3</div></li><!-- comment -->
                <li><div class='materials'>ch4</div></li><!-- comment -->
                <li><input type="text" name="newChapter"/><button type="submit">Add a  new chapter</button></li>
            <ul>
            </div>
            
            
            
            <div class='material-content' style='display:inline-block;border: 5px solid black;'>
                
                
                <form method="POST" action="{{ url('upload_file') }}" enctype="multipart/form-data">
    @csrf
    <label for="file">Choose a file:</label>
    <input type="file" id="file" name="file" required>
    <br><br>
    <button type="submit">Upload</button>
</form>

            
            
            
            
            
            
            </div><!-- comment -->
        </div>
        
        
        
    </body>
</html>
