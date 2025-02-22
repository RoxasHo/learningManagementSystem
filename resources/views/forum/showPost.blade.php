<x-layout>
  <link rel="stylesheet" href="{{ asset('css/show_posts.css') }}">



<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<div class="main-content" id="main-content">
    <div class="posts-container">

    <a href="{{ route('show.main') }}">
    <button class="back-to-home-btn">
    <span class="material-symbols-outlined">arrow_back</span>
    Back to Forum
    </button>
    </a>

        <div class="post">

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

        <h4>{{ $post->title }}</h4>
        <div class="post-content"><p>{!! $post->content !!}</p></div><br>
        <p>#{{ $post->tag }}</p>
        </div>
    </div>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

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
            <span class="material-symbols-outlined" 
            id="openReportModal" 
            data-post-id="{{ $post->post_id }}" 
            data-post-user-id="{{ $post->userID }}" 
            data-current-user-id="{{ auth()->user()->id }}">report</span>

            <div id="authorWarning" style="display: none; color: red;">
                You cannot report your own post.
            </div>
        </div>
    </div>
</div>


<div id="reportModal" class="report-modal" style="display: none;">
    <div class="report-modal-content">
        <span id="closeReportModal" class="close">&times;</span>
        <h2>Report Content</h2>
        <form method="POST" action="{{ route('report.store') }}">
            @csrf
            <label>Report Type:</label>
            <div class="checkbox-group">
                <label>
                    <input type="checkbox" name="reportType[]" value="Sensitive">
                    Contains controversial and sensitive content
                </label>
                <label>
                    <input type="checkbox" name="reportType[]" value="Violence">
                    Suspected verbal violence and threats
                </label>
                <label>
                    <input type="checkbox" name="reportType[]" value="Violation">
                    Violation of community etiquette
                </label>
                <label>
                    <input type="checkbox" name="reportType[]" value="Advertising">
                    Contains advertising or phishing sites
                </label>
            </div>
            <label for="customContent">Other:</label>
            <textarea id="customContent" name="customContent" rows="4" cols="50"></textarea>
            <input type="hidden" id="postId" name="postId" value="{{ $post->post_id }}">
            <input type="hidden" id="commentId" name="commentId" value="">
            <button type="submit">Submit Report</button>
        </form>
    </div>
</div>


<div class="divider"></div>
    
    <div class="write-comment">
    <h4>Write a Comment</h4>

            <form method="POST" action="{{ route('comment.store', $post->post_id) }}">
                @csrf
                <textarea id="editor" name="content"></textarea>
                <button type="submit">Post</button>
            </form>

         
        </div>
        </div>

        @php
    $visibleComments = $post->comments->where('is_visible', true)->whereNull('parent_comment_id');
@endphp

    <div class="comment-container">
    <h4>All Comments ({{ $post->comments->whereNull('parent_comment_id')->where('is_visible', true)->count() }})</h4>

    @if($visibleComments->isEmpty())
        <p>No comments yet.</p>
    @else
        <ul class="comments-list">
        @foreach($post->comments->whereNull('parent_comment_id')->where('is_visible', true) as $comment)
<div class="divider-post"></div>
<li class="comment-item">
<input type="hidden" class="comment-id" value="{{ $comment->comment_id }}">
    <!-- Comment author details -->
    <div class="comment-author-info">
                                    @if($comment->user->role === 'Teacher')
                                        <img src="{{ asset($comment->user->teacher->teacherPicture) }}" alt="{{ $comment->user->name }}" class="profile-image">
                                    @elseif($comment->user->role === 'Student')
                                        <img src="{{ $comment->user->student->studentPicture ? asset('storage/' . $comment->user->student->studentPicture) : asset('images/default-profile.png') }}" alt="{{ $comment->user->name }}" class="profile-image">
                                    @elseif($comment->user->role === 'Moderator')
                                        <img src="{{ asset($comment->user->moderator->moderatorPicture) }}" alt="{{ $comment->user->name }}" class="profile-image">
                                    @else
                                        <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile" class="default-image">
                                    @endif
    <div class="author-and-delete">
        <div class="author-details">
            <div class="comment-author"><strong>{{ $comment->user->name }}</strong></div>
            <div class="comment-date-role">
                <span class="bullet">&#8226;</span>
                <span class="comment-role">
                    @if($comment->userID == $post->userID)
                        Author
                    @else
                        {{ $comment->user->role }}
                    @endif
                </span>
            </div>
        </div>

       
    <div class="comment-actions">
        <span class="material-symbols-outlined more-button">
            more_vert
        </span>

        <!-- Hidden action menu with delete and report icons -->
        <div class="action-menu">
        @if(auth()->check() && auth()->user()->id == $comment->userID)
            <form action="{{ route('comment.destroy', $comment->comment_id) }}"   method="POST" class="delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-button" style="background: none; border: none; cursor: pointer;">
                    <span class="material-symbols-outlined delete-icon">delete</span>
                </button>
            </form>
        @endif
        @if(auth()->check() && auth()->user()->id != $comment->userID)
            <span class="material-symbols-outlined report-icon" id="openReportModal" id="openReportModal" data-post-id="{{ $post->post_id }}">report</span>
        @endif
        </div>
    </div>



    </div>
</div>
              

<p class="comment-container">{!! $comment->content !!}</p>





    <!-- Vote Forms for Comments -->
    <div class="comment-ratings-container" data-comment-id="{{ $comment->comment_id }}">
        <form class="comment-vote-form" action="{{ route('comment.like') }}" method="POST">
            @csrf
            <input type="hidden" name="comment_id" value="{{ $comment->comment_id }}">
            <input type="hidden" name="post_id" value="{{ $post->post_id }}">
            <button type="submit" class="vote-button" style="background: none; border: none; cursor: pointer;">
                <span class="comment-rating-button comment-like-button material-icons {{ $comment->userHasLiked($user) ? 'liked' : '' }}" data-type="like" data-comment-id="{{ $comment->comment_id }}">thumb_up</span>
                <span class="comment-rating-count" data-type="like" data-comment-id="{{ $comment->comment_id }}">{{ $comment->likes->count() }}</span>
            </button>
        </form>

        <form class="comment-vote-form" action="{{ route('comment.dislike') }}" method="POST">
            @csrf
            <input type="hidden" name="comment_id" value="{{ $comment->comment_id }}">
            <input type="hidden" name="post_id" value="{{ $post->post_id }}">
            <button type="submit" class="vote-button" style="background: none; border: none; cursor: pointer;">
                <span class="comment-rating-button comment-dislike-button material-icons {{ $comment->userHasDisliked($user) ? 'disliked' : '' }}" data-type="dislike" data-comment-id="{{ $comment->comment_id }}">thumb_down</span>
                <span class="comment-rating-count" data-type="dislike" data-comment-id="{{ $comment->comment_id }}">{{ $comment->dislikes->count() }}</span>
            </button>
        </form>
        <button class="reply-button" data-comment-id="{{ $comment->comment_id }}">
            <span class="material-symbols-outlined">reply_all</span>
            Reply
        </button>
    </div>
    <small>Posted on {{ $comment->created_at->format('F j, Y, g:i a') }}</small>

    <!-- Reply CKEditor -->
    <div class="reply-editor-container" id="reply-editor-{{ $comment->comment_id }}" style="display: none;">
        <form method="POST" action="{{ route('comment.reply', ['post_id' => $post->post_id, 'parent_comment_id' => $comment->comment_id]) }}" onsubmit="return validateReplyForm({{ $comment->comment_id }})">
            @csrf
            <textarea id="editor-{{ $comment->comment_id }}" name="content"></textarea>
            <button type="submit">Send Reply</button>
        </form>
    </div>

    <!-- Recursively display replies -->
    @include('partials.comments', ['comments' => $comment->replies, 'level' => 1])
</li>
@endforeach
        </ul>
    @endif
</div>
</div>
</x-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/show_individual_post.js') }}"></script>





