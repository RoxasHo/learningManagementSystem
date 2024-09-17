@extends('layouts.index')

@section('title', 'Posts for Tag: ' . $tag)

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/show_by_tag.css') }}">
@endpush

@section('content')
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<div class="posts-container">
    <h3>Topics Searched: #{{ $tag }}</h3>
    @if($posts->isEmpty())
        <p>No posts found for this tag.</p>
    @else
        @foreach($posts as $post)
        <a href="{{ route('post.show', $post->post_id) }}">
        <div class="post"></a>

<div class="post-author-info">
 <img src="{{ $post->user->student->studentPicture ? asset('storage/' . $post->user->student->studentPicture) : asset('images/default-profile.png') }}" alt="{{ $post->user->name }}'s profile image" class="profile-image">
<div class="author-details">
    <div class="post-author">{{ $post->user->name }}</div>
    <div class="post-date-role">
        <span class="post-date">{{ \App\Helpers\DateHelper::formatDate($post->created_at) }}</span>
        <span class="bullet">&#8226;</span> <!-- Bullet separator -->
        <span class="post-role">{{ $post->user->role }}</span>
    </div>
</div>
</div>


     <a href="{{ route('post.show', $post->post_id) }}" class="post-title">{{ $post->title }}</a>
    <div class="post-content">{!! $post->content !!}</div><br>
    <div class="post-actions">
        <div class="post-tag">#{{ $post->tag }}</div>
        <!-- Add Like and Dislike buttons here -->

    <div class="vote-button">
    <div class="post-ratings-container" data-post-id="{{ $post->post_id }}">
            <div class="post-rating">

            <form class="vote-form" action="{{ route('post.like') }}" method="POST" >
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->post_id }}">
                        <button type="submit" class="vote-button" style="background: none; border: none; cursor: pointer;">
            <span class="post-rating-button material-icons {{ $post->userHasLiked(Auth::user()) ? 'likedPost' : '' }}" data-type="like" data-post-id="{{ $post->post_id }}">thumb_up</span>
            
            <span class="post-rating-count" data-type="like" data-post-id="{{ $post->post_id }}">{{ $post->likes()->forPost()->count() }}</span>
            </button>
            </form>

            <form class="vote-form" action="{{ route('post.dislike') }}" method="POST" style="display:inline;">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->post_id }}">
            <div class="post-rating">
            <button type="submit" class="vote-button" style="background: none; border: none; cursor: pointer;">
                <span class="post-rating-button material-icons {{ $post->userHasDisliked(Auth::user()) ? 'dislikedPost' : '' }}"data-type="dislike" data-post-id="{{ $post->post_id }}">thumb_down</span>
                
                <span class="post-rating-count" data-type="dislike" data-post-id="{{ $post->post_id }}">{{ $post->dislikes()->forPost()->count() }}</span>
            </div>
            </button>
            </form>
            

            



                <a href="{{ route('post.show', $post->post_id) }}" class="post-title" style="font-weight: normal; ">
                <div class="post-comment">
                    <span class="material-symbols-outlined">
                        chat  
                    </span>
                    <span class="post-comment-icon">&nbsp{{ $post->comments->whereNull('parent_comment_id')->count() }} </span>
                </div>
                </a>
            </div>
            </div>
</div>
    </div>
</div>
@endforeach
        {{ $posts->links('vendor.pagination.bootstrap-5') }}
    @endif
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll(".vote").forEach(post => {
        const postId = post.dataset.postId;
        const ratings = post.querySelectorAll(".post-rating");

        ratings.forEach(rating => {
            const button = rating.querySelector(".post-rating-button");
            const count = rating.querySelector(".post-rating-count");

            button.addEventListener("click", async () => {
                if (rating.classList.contains("post-rating-selected")) {
                    return;
                }

                // Increment the count for the clicked button
                count.textContent = Number(count.textContent) + 1;

                // Loop through other ratings and remove selection
                ratings.forEach(otherRating => {
                    if (otherRating !== rating && otherRating.classList.contains("post-rating-selected")) {
                        const otherCount = otherRating.querySelector(".post-rating-count");
                        otherCount.textContent = Math.max(0, Number(otherCount.textContent) - 1);
                        otherRating.classList.remove("post-rating-selected");
                    }
                });

                // Mark the clicked button as selected
                rating.classList.add("post-rating-selected");

                // Determine like or dislike and send fetch request
                const likeOrDislike = rating.dataset.type;
                await fetch(`/showPost/${postId}/vote/${likeOrDislike}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                });
            });
        });
    });
});


</script>
@endsection
