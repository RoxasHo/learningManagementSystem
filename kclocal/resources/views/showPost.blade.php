@extends('layouts.index')

@section('title', $post->title)

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/show_posts.css') }}">
@endpush

@section('content')


<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>

    <div class="posts-container">
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

        <h4>{{ $post->title }}</h4>
        <p>{!! $post->content !!}</p><br>
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
            <span class="material-symbols-outlined" id="openReportModal" data-post-id="{{ $post->post_id }}">report</span>
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
    <img src="{{ $comment->user->student->studentPicture ? asset('storage/' . $comment->user->student->studentPicture) : asset('images/default-profile.png') }}" alt="{{ $comment->user->name }}'s profile image" class="profile-image">
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
              

    <p>{!! $comment->content !!}</p>





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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// Attach click event to all more-button elements
document.querySelectorAll('.more-button').forEach(function(button) {
    button.addEventListener('click', function() {
        // Find the corresponding action menu within the same comment
        const actionMenu = this.parentElement.querySelector('.action-menu');
        
        // Toggle the action menu display
        actionMenu.classList.toggle('show-menu');
    });
});

// Optional: Hide the action menu when clicking outside
document.addEventListener('click', function(event) {
    document.querySelectorAll('.action-menu').forEach(function(actionMenu) {
        const moreButton = actionMenu.parentElement.querySelector('.more-button');
        
        // Close the menu if clicked outside
        if (!actionMenu.contains(event.target) && !moreButton.contains(event.target)) {
            actionMenu.classList.remove('show-menu');
        }
    });
});


document.querySelectorAll('#openReportModal').forEach(button => {
    button.addEventListener('click', function() {
        const postId = this.getAttribute('data-post-id');
        const commentId = this.closest('.comment-item') ? this.closest('.comment-item').querySelector('.comment-id').value : null;

        document.getElementById('postId').value = postId;
        document.getElementById('commentId').value = commentId || ''; // Set commentId if available

        document.getElementById('reportModal').style.display = 'block';
    });
});

document.getElementById('closeReportModal').addEventListener('click', function() {
    document.getElementById('reportModal').style.display = 'none';
});





let mainEditorInstance;
let replyEditors = {};

ClassicEditor
    .create(document.querySelector('#editor'), {
        ckfinder: {
            uploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
        }
    })
    .then(editor => {
        mainEditorInstance = editor;
    })
    .catch(error => {
        console.error('Error initializing main editor:', error);
    });

function validateMainForm() {
    const content = mainEditorInstance.getData().trim();

    if (content === '') {
        alert('Content cannot be blank.');
        return false;
    }

    return true;
}

document.querySelectorAll('.reply-button').forEach(button => {
    button.addEventListener('click', function() {
        const commentId = this.getAttribute('data-comment-id');
        const editorContainer = document.querySelector(`#reply-editor-${commentId}`);
        
        if (editorContainer.style.display === 'none') {
            editorContainer.style.display = 'block';

            if (!replyEditors[commentId]) {
                ClassicEditor
                    .create(document.querySelector(`#editor-${commentId}`), {
                        ckfinder: {
                            uploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
                        }
                    })
                    .then(editor => {
                        replyEditors[commentId] = editor;
                    })
                    .catch(error => {
                        console.error('Error initializing reply editor:', error);
                    });
            }
        } else {
            editorContainer.style.display = 'none';
            if (replyEditors[commentId]) {
                replyEditors[commentId].destroy();
                delete replyEditors[commentId];
            }
        }
    });
});

function validateReplyForm(commentId) {
    const content = replyEditors[commentId]?.getData().trim();

    if (content === '') {
        alert('Content cannot be blank.');
        return false;
    }

    return true;
}

$(document).ready(function() {
    // Handle post like/dislike form submission
    $('.post-vote-form').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        var $form = $(this);
        var formData = $form.serialize(); // Serialize the form data
        var action = $form.attr('action'); // Get the action (like or dislike)

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                // Update the like/dislike count for posts
                var $ratingContainer = $form.closest('.post-ratings-container');
                var postId = $ratingContainer.data('post-id');

                $ratingContainer.find('.post-rating-count[data-type="like"][data-post-id="' + postId + '"]').text(response.likesCount);
                $ratingContainer.find('.post-rating-count[data-type="dislike"][data-post-id="' + postId + '"]').text(response.dislikesCount);

                // Update the button colors for posts
                if (response.userVote === 'like') {
                    $ratingContainer.find('.post-rating-button.post-like-button[data-type="like"][data-post-id="' + postId + '"]').addClass('likedPost');
                    $ratingContainer.find('.post-rating-button.post-dislike-button[data-type="dislike"][data-post-id="' + postId + '"]').removeClass('dislikedPost');
                } else if (response.userVote === 'dislike') {
                    $ratingContainer.find('.post-rating-button.post-dislike-button[data-type="dislike"][data-post-id="' + postId + '"]').addClass('dislikedPost');
                    $ratingContainer.find('.post-rating-button.post-like-button[data-type="like"][data-post-id="' + postId + '"]').removeClass('likedPost');
                } else {
                    $ratingContainer.find('.post-rating-button.post-like-button[data-type="like"][data-post-id="' + postId + '"]').removeClass('likedPost');
                    $ratingContainer.find('.post-rating-button.post-dislike-button[data-type="dislike"][data-post-id="' + postId + '"]').removeClass('dislikedPost');
                }
            },
            error: function(xhr) {
                // Handle any errors
                console.log(xhr.responseText);
            }
        });
    });

    // Handle comment like/dislike form submission
    $('.comment-vote-form').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        var $form = $(this);
        var formData = $form.serialize(); // Serialize the form data
        var action = $form.attr('action'); // Get the action (like or dislike)

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                // Update the like/dislike count for comments
                var $ratingContainer = $form.closest('.comment-ratings-container');
                var commentId = $ratingContainer.data('comment-id');

                $ratingContainer.find('.comment-rating-count[data-type="like"][data-comment-id="' + commentId + '"]').text(response.likesCount);
                $ratingContainer.find('.comment-rating-count[data-type="dislike"][data-comment-id="' + commentId + '"]').text(response.dislikesCount);

                // Update the button colors for comments
                if (response.userVote === 'like') {
                    $ratingContainer.find('.comment-rating-button.comment-like-button[data-type="like"][data-comment-id="' + commentId + '"]').addClass('liked');
                    $ratingContainer.find('.comment-rating-button.comment-dislike-button[data-type="dislike"][data-comment-id="' + commentId + '"]').removeClass('disliked');
                } else if (response.userVote === 'dislike') {
                    $ratingContainer.find('.comment-rating-button.comment-dislike-button[data-type="dislike"][data-comment-id="' + commentId + '"]').addClass('disliked');
                    $ratingContainer.find('.comment-rating-button.comment-like-button[data-type="like"][data-comment-id="' + commentId + '"]').removeClass('liked');
                } else {
                    $ratingContainer.find('.comment-rating-button.comment-like-button[data-type="like"][data-comment-id="' + commentId + '"]').removeClass('liked');
                    $ratingContainer.find('.comment-rating-button.comment-dislike-button[data-type="dislike"][data-comment-id="' + commentId + '"]').removeClass('disliked');
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
