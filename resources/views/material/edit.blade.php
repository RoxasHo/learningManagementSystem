<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="{{ asset('assets/css/PagesComponent.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/EditPage.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.1.0/ckeditor5.css" />
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5-premium-features/43.1.0/ckeditor5-premium-features.css" />
</head>
<body class="App">
<header class="header">  
    <nav>
        <ul class="nav-links">
            <li><a href="#">Search</a></li>
            <li><a href="#">Forum</a></li>
            <li><a href="#">Subscription</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Login</a></li>
            <li><a href="#">Profile</a></li>
        </ul>
    </nav>
</header>
<aside class="sidebar">
    <ul>
        <nav>
            <li><a href="#">Search</a></li>
            <li><a href="{{ url('teacher-course',['id'=>1]) }}">Courses</a></li>
            <li><a href="/CourseEditPage">Edit</a></li>
            <li><a href="#">Search</a></li>
        </nav>
    </ul>
</aside>
<div class="main-content">
    <!-- Form for saving content -->
    <form style="float:right" method="POST" action="{{ route('save-material') }}">
        @csrf
        <label>Content</label>
        
        <!-- Hidden field for the material ID -->
        <input type="hidden" name="id" value="{{ $material->id }}">

        <!-- Text area for CKEditor -->
        <textarea id="editor" name="content">
            {{ $material->content }}
        </textarea>
        <input type="hidden" value="{{$material->updated_at}}" name="last_update_time">
        <!-- Submit button -->
        <div>
            <input type="submit" value="Save Content"/>
        </div>
    </form>

    
</div>
<footer class="footer">Footer</footer>
<script type="importmap">
            {
                "imports": {
                    "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.1.0/ckeditor5.js",
                    "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.1.0/"
                }
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

ClassicEditor
  .create(document.querySelector('#editor'), {
    image: {
            toolbar: [ 'toggleImageCaption', 'imageTextAlternative', 'ckboxImageEdit' ]
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
    console.log('Editor was initialized successfully.');
  })
  .catch(error => {
    console.error('Error initializing editor:', error);
  });
</script>

</body>
</html>
