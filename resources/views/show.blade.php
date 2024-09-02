@extends('layouts.index')

@section('title', 'Community')

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/forum_main.css') }}">
@endpush

@section('content')

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <div class="forum-header">
    <h2>Latest Posts</h2>

        <div class="search-container" style="margin-left: 1%;">
        <form method="GET" action="{{ route('posts.search') }}">
        <input type="text" name="search" placeholder="Search for something..." value="{{ request('search') }}" class="search-input">
        <button type="submit" class="search-button">Search</button>
        </form>
        </div>
    </div>

    <div class="tabs-header">
        <div class="tab-button active" data-tab="tab1">Recent</div>
        <div class="tab-button" data-tab="tab2">Following</div>
    </div>

    <div class="whole-container">
    <div class="forum">
  
    <div class="tabs-content">
    <div class="tab-panel active" id="tab1">
        @foreach($posts as $post)
        <div class="post">
            <a href="{{ route('post.show', $post->post_id) }}" class="post-title">{{ $post->title }}</a>
            <div class="post-content">{!! $post->content !!}</div><br>
            <div class="post-actions">
                <div class="post-tag">#{{ $post->tag }}</div>
                <!-- Add Like and Dislike buttons here -->
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
                        <a href="{{ route('post.show', $post->post_id) }}" class="post-title" style="font-weight: normal; ">
                        <div class="post-comment">
                            <span class="material-symbols-outlined">
                                chat  
                            </span>
                            {{ $post->comments->whereNull('parent_comment_id')->count() }}
                        </div>
                        </a>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        {{ $posts->links('vendor.pagination.bootstrap-5') }}
    </div>
    </div>

    <div class="tab-panel" id="tab2" style="display: none;">
            <!-- Content for the Following tab (currently empty) -->
            
        </div>
    </div>

    <div class="tags" style="margin-top: 1%;">
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
document.addEventListener('DOMContentLoaded', function () {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabButtons.forEach(button => {
        button.addEventListener('click', function () {
            const target = button.getAttribute('data-tab');

            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanels.forEach(panel => panel.classList.remove('active'));

            button.classList.add('active');
            document.getElementById(target).classList.add('active');
        });
    });
});

    
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
