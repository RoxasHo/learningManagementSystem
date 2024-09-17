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
                    <span class="post-rating-button material-icons {{ $post->userHasLiked($user) ? 'likedPost' : '' }}" data-type="like" data-post-id="{{ $post->post_id }}">thumb_up</span>
                    
                    <span class="post-rating-count" data-type="like" data-post-id="{{ $post->post_id }}">{{ $post->likes()->forPost()->count() }}</span>
                    </button>
                    </form>

                    <form class="vote-form" action="{{ route('post.dislike') }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->post_id }}">
                    <div class="post-rating">
                    <button type="submit" class="vote-button" style="background: none; border: none; cursor: pointer;">
                        <span class="post-rating-button material-icons {{ $post->userHasDisliked($user) ? 'dislikedPost' : '' }}"data-type="dislike" data-post-id="{{ $post->post_id }}">thumb_down</span>
                        
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
    </div>
    </div>

    <div class="tab-panel" id="tab2">
    <!-- Content for the "Following" tab will be injected here via AJAX -->
    <div id="following-posts-container"></div>
</div>
        

        
    </div>

    <div class="sidebar-container" style="position: relative; ">
    <div class="add-button-container">
    <a href="{{ route('create.page') }}">
        <button class="add-button">Add Post</button>
    </a>
    <a href="{{ route('view.mypost') }}">
        <button class="mypost-button">My Post</button>
    </a>
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

    
 
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent page reload

            const target = button.getAttribute('data-tab');

            // Remove active class from all buttons and panels
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanels.forEach(panel => panel.classList.remove('active'));

            // Add active class to the clicked button and corresponding panel
            button.classList.add('active');
            document.getElementById(target).classList.add('active');

            // Load the following tab content dynamically
            if (target === 'tab2') {
                fetchFollowingPosts();
            }
        });
    });

    function fetchFollowingPosts() {
        fetch("{{ route('community.index') }}?tab=following", {
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // Make sure it's recognized as an AJAX request
            }
        })
        .then(response => response.text())
        .then(html => {
            // Inject the content into a specific container inside the tab2 panel
            document.getElementById('following-posts-container').innerHTML = html;
        })
        .catch(error => console.error('Error fetching following posts:', error));
    }
});

$(document).ready(function() {
    $('.vote-form').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        var $form = $(this);
        var formData = $form.serialize(); // Serialize the form data
        var action = $form.attr('action'); // Get the action (like or dislike)

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                // Update the like/dislike count
                var $ratingContainer = $form.closest('.post-ratings-container');
                var postId = $ratingContainer.data('post-id');
                $ratingContainer.find('.post-rating-count[data-type="like"][data-post-id="' + postId + '"]').text(response.likesCount);
                $ratingContainer.find('.post-rating-count[data-type="dislike"][data-post-id="' + postId + '"]').text(response.dislikesCount);

                // Update the button colors
                if (response.userVote === 'like') {
                    $ratingContainer.find('.post-rating-button[data-type="like"][data-post-id="' + postId + '"]').addClass('likedPost');
                    $ratingContainer.find('.post-rating-button[data-type="dislike"][data-post-id="' + postId + '"]').removeClass('dislikedPost');
                } else if (response.userVote === 'dislike') {
                    $ratingContainer.find('.post-rating-button[data-type="dislike"][data-post-id="' + postId + '"]').addClass('dislikedPost');
                    $ratingContainer.find('.post-rating-button[data-type="like"][data-post-id="' + postId + '"]').removeClass('likedPost');
                } else {
                    $ratingContainer.find('.post-rating-button[data-type="like"][data-post-id="' + postId + '"]').removeClass('likedPost');
                    $ratingContainer.find('.post-rating-button[data-type="dislike"][data-post-id="' + postId + '"]').removeClass('dislikedPost');
                }
            },
            error: function(xhr) {
                // Handle any errors
                console.log(xhr.responseText);
            }
        });
    });
});







</script>

@endsection
