@extends('layouts.index')

@section('title', 'All Topics')

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/show_tags.css') }}">
@endpush

@section('content')
<h3>Hot Topics</h3>

<!-- Search bar for filtering tags -->
<div class="search-container">
    <form method="GET" action="{{ route('tags.search') }}">
        <input type="text" name="search" placeholder="Search tags..." value="{{ request('search') }}" class="search-input">
        <button type="submit" class="search-button">Search</button>
    </form>
</div>


<div class="tags-container">     
    <div class="tags-list">
        @if(request('search'))
            @if($searchedTags->isNotEmpty())
                @foreach($searchedTags as $tag)
                    <div class="tag">
                        <a href="{{ route('posts.byTag', ['tag' => $tag]) }}">#{{ $tag }}</a>
                    </div>
                @endforeach
            @else
                <p>No matching tags found.</p>
            @endif
        @else
            @foreach($tagsArray as $tag)
                <div class="tag">
                    <a href="{{ route('posts.byTag', ['tag' => $tag]) }}">#{{ $tag }}</a>
                </div>
            @endforeach
        @endif
    </div>
</div>

@endsection
