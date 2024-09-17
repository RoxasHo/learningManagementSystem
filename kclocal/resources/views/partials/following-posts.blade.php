@if($followedPosts->isNotEmpty())
    @foreach($followedPosts as $post)
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
{{ $followedPosts->links('vendor.pagination.bootstrap-5') }}
@else
    <p>No posts available for the tags you follow.</p>
@endif
