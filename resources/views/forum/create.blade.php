<x-layout>
<link rel="stylesheet" href="{{ asset('css/create_post.css') }}">
<div class="main-content" id="main-content">
<div class="create-post">
<h1>Create a Post</h1>
    <form action="{{ url('create') }}" method="POST" onsubmit="return validateForm()">
        @csrf
        <label for="title">1.&nbsp;Title</label><br>
        <input type="text" id="title-style" name="title" required><br>

        <label for="content">2.&nbsp;Content</label>
        <textarea id="editor" name="content"></textarea>

        <label for="tags">3.&nbsp;Tags</label><br>
        <input type="text" id="tag-style" name="tag" placeholder="e.g. (java, php)" required>

        <div class="create-button-style">
            <button class="cancel-button" onclick="window.location='{{ route('show.main') }}'" type="button">Cancel</button>
            <input class="publish-button" type="submit" value="Publish">
        </div>
    </form>
</div>

</div>
</x-layout>

<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<script src="{{ asset('js/create_post.js') }}"></script>

