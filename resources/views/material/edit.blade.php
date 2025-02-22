<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.1.0/ckeditor5.css" />
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5-premium-features/43.1.0/ckeditor5-premium-features.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/materialEdit.css')}}">
<x-layout>
<div class="main-content" id="main-content" style="padding: 100px 150px; min-height: 700px">
    <!-- Form for saving content -->
    <form class="material-form" method="POST" action="{{ route('save-material') }}">
        @csrf
        <div class="form-group">
            <label for="editor">Content</label>
            <!-- Hidden field for the material ID -->
            <input type="hidden" name="id" value="{{ $material->id }}">
            <!-- Text area for CKEditor -->
            <textarea id="editor" name="content">{{ $material->content }}</textarea>
            <input type="hidden" value="{{$material->updated_at}}" name="last_update_time">
        </div>
        <!-- Submit button -->
        <div class="form-group">
            <input type="submit" value="Save Content" class="btn btn-primary save-button"/>
        </div>
    </form>
</div>

</x-layout>
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
  CodeBlock
} from 'ckeditor5';

ClassicEditor
  .create(document.querySelector('#editor'), {
    image: {
            toolbar: [ 'toggleImageCaption', 'imageTextAlternative', 'ckboxImageEdit','imageUpload' ]
        },
        codeBlock: {
            languages: [
              { language: 'plaintext', label: 'Plain text' }, // The default language.
    { language: 'c', label: 'C' },
    { language: 'cs', label: 'C#' },
    { language: 'cpp', label: 'C++' },
    { language: 'css', label: 'CSS' },
    { language: 'diff', label: 'Diff' },
    { language: 'html', label: 'HTML' },
    { language: 'java', label: 'Java' },
    { language: 'javascript', label: 'JavaScript' },
    { language: 'php', label: 'PHP' },
    { language: 'python', label: 'Python' },
    { language: 'ruby', label: 'Ruby' },
    { language: 'typescript', label: 'TypeScript' },
    { language: 'xml', label: 'XML' }
            ]
        },
    plugins: [
      Essentials, Bold, Italic, Font, Paragraph, Alignment, Heading,
      Link, List, Image, ImageToolbar, ImageCaption, ImageStyle, Base64UploadAdapter,CodeBlock,
      BlockQuote, Table, TableToolbar
    ],
    toolbar: {
      items: [
        'heading', '|',
        'bold', 'italic', 'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
        'alignment', 'link', '|',
        'bulletedList', 'numberedList', '|',
        'imageUpload', 'blockQuote', 'insertTable', '|',
        'codeBlock',
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
