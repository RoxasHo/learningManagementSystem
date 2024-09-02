@extends('layouts.index')

@section('title', 'Posts for Tag: ' . $tag)

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/show_posts.css') }}">
@endpush

@section('content')
<div class="posts-container">
    <h3>Topics Searched: #{{ $tag }}</h3>
    @if($posts->isEmpty())
        <p>No posts found for this tag.</p>
    @else
        @foreach($posts as $post)
        <a href="{{ route('post.show', $post->post_id) }}">
            <div class="post">
                <h4>{{ $post->title }}</h4>
                <p>{!! $post->content !!}</p><br>
                <p>#{{ $post->tag }}</p>
            </div>
        </a>
        @endforeach
        {{ $posts->links('vendor.pagination.bootstrap-5') }}
    @endif
</div>
@endsection
