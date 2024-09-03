@extends('layouts.index')

@section('title', $post->title)

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/show_posts.css') }}">
@endpush

@section('content')


<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>

    <div class="posts-container">
        <div class="post">
        <h4>{{ $post->title }}</h4>
        <div class="post-date" style="font-size: 15px; color: black;">
            {{ \App\Helpers\DateHelper::formatDate($post->created_at) }}
        </div>
        <p>{!! $post->content !!}</p><br>
        <p>#{{ $post->tag }}</p>
        </div>
    </div>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <div class="vote-button">
    <div class="vote" data-post-id="{{ $post->post_id }}">
        <div class="post-ratings-container">
            <div class="post-rating">
                <span class="post-rating-button material-icons" data-type="like" data-voted="false">thumb_up</span>
                <span class="post-rating-count">{{ $post->like_count }}</span>
            </div>
            <div class="post-rating">
                <span class="post-rating-button material-icons" data-type="dislike" data-voted="false">thumb_down</span>
                <span class="post-rating-count">{{ $post->dislike_count }}</span>
            </div>
            <span class="material-symbols-outlined" id="openReportModal">report</span>
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
            <input type="hidden" id="postId" name="postId" value="">
            <!-- Removed commentId -->
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

    <div class="divider"></div>

    <div class="comment-container">
    <h4>All Comments ({{ $post->comments->whereNull('parent_comment_id')->count() }})</h4>

    @if($post->comments->isEmpty())
        <p>No comments yet.</p>
    @else
        <ul class="comments-list">
            @foreach($post->comments->whereNull('parent_comment_id') as $comment) <!-- Only top-level comments -->
                <li class="comment-item">
                    <p>{!! $comment->content !!}</p>
                    <small>Posted on {{ $comment->created_at->format('F j, Y, g:i a') }}</small>
                    <button class="reply-button" data-comment-id="{{ $comment->comment_id }}">Reply</button>

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

<script>
document.getElementById("openReportModal").addEventListener("click", function() {
    document.getElementById("reportModal").style.display = "block";
    // Set the postId field with the current post's ID
    document.getElementById("postId").value = '{{ $post->post_id }}'; 
});

document.getElementById("closeReportModal").addEventListener("click", function() {
    document.getElementById("reportModal").style.display = "none";
});

window.addEventListener("click", function(event) {
    if (event.target == document.getElementById("reportModal")) {
        document.getElementById("reportModal").style.display = "none";
    }
});


  document.querySelectorAll(".vote").forEach(post => {
    const postId = post.dataset.postId;
    const ratings = post.querySelectorAll(".post-rating");

    ratings.forEach(rating => {
        const button = rating.querySelector(".post-rating-button");
        const count = rating.querySelector(".post-rating-count");

        button.addEventListener("click", async () => {
            const voteType = button.dataset.type;
            const voted = button.dataset.voted === 'true';

            if (voted) {
                // Remove the vote
                count.textContent = Math.max(0, Number(count.textContent) - 1);
                button.dataset.voted = 'false';

                const response = await fetch(`/showPost/${postId}/vote/${voteType}/remove`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                });

                const body = await response.json();
                document.querySelector(`[data-post-id="${postId}"] .post-rating[data-type="like"] .post-rating-count`).textContent = body.likes;
                document.querySelector(`[data-post-id="${postId}"] .post-rating[data-type="dislike"] .post-rating-count`).textContent = body.dislikes;

            } else {
                count.textContent = Number(count.textContent) + 1;
                button.dataset.voted = 'true';

                ratings.forEach(rating => {
                    if (rating !== button.parentElement) {
                        const otherButton = rating.querySelector(".post-rating-button");
                        if (otherButton.dataset.voted === 'true') {
                            const otherCount = rating.querySelector(".post-rating-count");
                            otherCount.textContent = Math.max(0, Number(otherCount.textContent) - 1);
                            otherButton.dataset.voted = 'false';

                            fetch(`/showPost/${postId}/vote/${otherButton.dataset.type}/remove`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({})
                            });
                        }
                    }
                });

                const response = await fetch(`/showPost/${postId}/vote/${voteType}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                });

                const body = await response.json();
                document.querySelector(`[data-post-id="${postId}"] .post-rating[data-type="like"] .post-rating-count`).textContent = body.likes;
                document.querySelector(`[data-post-id="${postId}"] .post-rating[data-type="dislike"] .post-rating-count`).textContent = body.dislikes;
            }
        });
    });
});

document.querySelectorAll(".comment-vote").forEach(comment => {
    const commentId = comment.dataset.commentId;
    const ratings = comment.querySelectorAll(".comment-rating");

    ratings.forEach(rating => {
        const button = rating.querySelector(".comment-rating-button");
        const count = rating.querySelector(".comment-rating-count");

        button.addEventListener("click", async () => {
            const voteType = button.dataset.type;
            const voted = button.dataset.voted === 'true';

            if (voted) {
                // Remove the vote
                count.textContent = Math.max(0, Number(count.textContent) - 1);
                button.dataset.voted = 'false';

                const response = await fetch(`/showComment/${commentId}/vote/${voteType}/remove`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                });

                const body = await response.json();
                document.querySelector(`[data-comment-id="${commentId}"] .comment-rating[data-type="like"] .comment-rating-count`).textContent = body.likes;
                document.querySelector(`[data-comment-id="${commentId}"] .comment-rating[data-type="dislike"] .comment-rating-count`).textContent = body.dislikes;

            } else {
                count.textContent = Number(count.textContent) + 1;
                button.dataset.voted = 'true';

                ratings.forEach(rating => {
                    if (rating !== button.parentElement) {
                        const otherButton = rating.querySelector(".comment-rating-button");
                        if (otherButton.dataset.voted === 'true') {
                            const otherCount = rating.querySelector(".comment-rating-count");
                            otherCount.textContent = Math.max(0, Number(otherCount.textContent) - 1);
                            otherButton.dataset.voted = 'false';

                            fetch(`/showComment/${commentId}/vote/${otherButton.dataset.type}/remove`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({})
                            });
                        }
                    }
                });

                const response = await fetch(`/showComment/${commentId}/vote/${voteType}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                });

                const body = await response.json();
                document.querySelector(`[data-comment-id="${commentId}"] .comment-rating[data-type="like"] .comment-rating-count`).textContent = body.likes;
                document.querySelector(`[data-comment-id="${commentId}"] .comment-rating[data-type="dislike"] .comment-rating-count`).textContent = body.dislikes;
            }
        });
    });
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
</script>



@endsection
