<x-layout>

  <link rel="stylesheet" href="{{ asset('css/forum_main.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<div class="main-content" id="main-content">
    <div class="whole-container">
    <div class="forum">
        
    <h4 style="display: flex; font-size: 1.8rem;">Continue Search?</h4>
        <div class="search-container">
    <form method="GET" action="{{ route('posts.search') }}">
        <input type="text" name="search" placeholder="Search for something..." value="{{ request('search') }}" class="search-input">
        <button type="submit" class="search-button">Search</button>
    </form>
    <a href="{{ route('show.main') }}">
    <button class="back-to-home-btn">
    <span class="material-symbols-outlined">arrow_back</span>
    Back
    </button>
    </a>
</div>



@if(isset($search) && $search)
    <h4>Search Results for: "{{ $search }}"</h4>
    @if($posts->isEmpty())
        <p style="color: black; margin-top:5%; display: flex;justify-content:center;">No matching results found.</p>
    @else
        @foreach($posts as $post)
        <div class="post" style="margin-left: 13%;">

<div class="post-author-info">
@if($post->user->role === 'Teacher')
                                        <img src="{{ asset($post->user->teacher->teacherPicture) }}" alt="{{ $post->user->name }}" class="profile-image">
                                    @elseif($post->user->role === 'Student')
                                        <img src="{{ $post->user->student->studentPicture ? asset('storage/' . $post->user->student->studentPicture) : asset('images/default-profile.png') }}" alt="{{ $post->user->name }}" class="profile-image">
                                    @elseif($post->user->role === 'Moderator')
                                        <img src="{{ asset($post->user->moderator->moderatorPicture) }}" alt="{{ $post->user->name }}" class="profile-image">
                                    @else
                                        <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile" class="default-image">
                                    @endif
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

            <form class="post-vote-form" action="{{ route('post.like') }}" method="POST">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->post_id }}">
                <button type="submit" class="vote-button" style="background: none; border: none; cursor: pointer;">
                    <span class="post-rating-button post-like-button material-icons {{ $post->userHasLiked($user) ? 'likedPost' : '' }}" data-type="like" data-post-id="{{ $post->post_id }}">thumb_up</span>
                    <span class="post-rating-count" data-type="like" data-post-id="{{ $post->post_id }}">{{ $post->likes()->forPost()->count() }}</span>
                </button>
            </form>

            <form class="post-vote-form" action="{{ route('post.dislike') }}" method="POST" style="display:inline;">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->post_id }}">
                <button type="submit" class="vote-button" style="background: none; border: none; cursor: pointer;">
                    <span class="post-rating-button post-dislike-button material-icons {{ $post->userHasDisliked($user) ? 'dislikedPost' : '' }}" data-type="dislike" data-post-id="{{ $post->post_id }}">thumb_down</span>
                    <span class="post-rating-count" data-type="dislike" data-post-id="{{ $post->post_id }}">{{ $post->dislikes()->forPost()->count() }}</span>
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
@endif
</div>

</div>   
</x-layout>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/show_search_post.js') }}"></script>



