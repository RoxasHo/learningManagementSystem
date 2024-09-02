@extends('layouts.index')

@section('title', 'Community')

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/forum_main.css') }}">
@endpush

@section('content')
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <div class="whole-container">
    <div class="forum">
        

        <div class="search-container" style="padding-top: 4%;">
    <form method="GET" action="{{ route('posts.search') }}">
        <input type="text" name="search" placeholder="Search for something..." value="{{ request('search') }}" class="search-input">
        <button type="submit" class="search-button">Search</button>
    </form>
</div>



@if(isset($search) && $search)
    <h4>Search Results for: "{{ $search }}"</h4>
    @if($posts->isEmpty())
        <p style="color: white; margin-left:13%">No matching results found.</p>
    @else
        @foreach($posts as $post)
            <div class="post">
                <a href="{{ route('post.show', $post->post_id) }}" class="post-title">{{ $post->title }}</a>
                <div class="post-content">{!! $post->content !!}</div><br>
                <div class="post-actions">
                    <div class="post-tag">#{{ $post->tag }}</div>
                    <div class="vote-button">
                        <div class="vote" data-post-id="{{ $post->post_id }}">
                            <div class="post-ratings-container">
                                <div class="post-rating" data-type="like">
                                    <span class="post-rating-button material-icons" data-type="like">thumb_up</span>
                                    <span class="post-rating-count">{{ $post->like_count }}</span>
                                </div>
                                <div class="post-rating" data-type="dislike">
                                    <span class="post-rating-button material-icons" data-type="dislike">thumb_down</span>
                                    <span class="post-rating-count">{{ $post->dislike_count }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        {{ $posts->links('vendor.pagination.bootstrap-5') }}
    @endif
@endif
</div>

    <div class="tags">
        <h3>Hot Topics</h3>
        @foreach($tagsArray as $tag)
            <div class="tag"><a href="{{ route('posts.byTag', ['tag' => $tag]) }}">#{{ $tag }}</a></div>
        @endforeach
        <div class="view-more">
            <a href="{{ route('showtags') }}">View All</a>
        </div> 
    </div>
</div>


<div class="add-button">
    <a href="{{ route('create.page') }}">
        <img src="{{ asset('add_button.png') }}" alt="Add Post" />   
    </a>
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
