@extends('layouts.index')

@section('title', 'Create Form')

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/create_post.css') }}">
@endpush

@section('content')
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<div class="create-post">
    <form action="{{ url('create') }}" method="POST" onsubmit="return validateForm()">
        @csrf
        <label for="title">1.&nbsp;Title</label><br>
        <input type="text" id="title-style" name="title" required><br>

        <label for="content">2.&nbsp;Content</label>
        <textarea id="editor" name="content"></textarea>

        <label for="tags">3.&nbsp;Tags</label><br>
        <input type="text" id="tag-style" name="tag" placeholder="e.g. (java, php)" required>

        <div class="create-button-style">
            <button onclick="window.location='{{ route('show.main') }}'" type="button">Cancel</button>
            <input type="submit" value="Publish">
        </div>
    </form>
</div>



<script>
    let editorInstance;

    ClassicEditor
        .create(document.querySelector('#editor'), {
            ckfinder: {
                uploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            }
        })
        .then(editor => {
            editorInstance = editor;
        })
        .catch(error => {
            console.error(error);
        });

    // Custom validation function
    function validateForm() {
        const content = editorInstance.getData().trim();

        if (content === '') {
            alert('Content cannot be blanked.');
            return false;
        }

        return true;
    }
</script>
@endsection

