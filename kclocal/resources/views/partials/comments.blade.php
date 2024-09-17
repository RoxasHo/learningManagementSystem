@if($comments->isNotEmpty())
    <ul class="comments-list">
        @foreach($comments as $reply)
        <div class="reply-indent" style="margin-left: 20px;">
            <li class="comment-item">
                <!-- Comment author details -->
                <div class="comment-author-info">
                <img src="{{ $reply->user->student->studentPicture ? asset('storage/' . $reply->user->student->studentPicture) : asset('images/default-profile.png') }}" alt="{{ $reply->user->name }}'s profile image" class="profile-image">
                    <div class="author-details">
                        <div class="comment-author">
                            <strong>{{ $reply->user->name }}</strong> replied to <strong>{{ $comment->user->name }}</strong>
                        </div>
                        <div class="comment-date-role">
                            <span class="bullet">&#8226;</span> <!-- Bullet separator -->
                            <span class="comment-role">
                                @if($reply->userID == $post->userID)
                                    Author
                                @else
                                    {{ $reply->user->role }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <p>{!! $reply->content !!}</p>

                <!-- Vote Forms for Comments -->
                <div class="comment-ratings-container" data-comment-id="{{ $reply->comment_id }}">
                    <form class="comment-vote-form" action="{{ route('comment.like') }}" method="POST">
                        @csrf
                        <input type="hidden" name="comment_id" value="{{ $reply->comment_id }}">
                        <input type="hidden" name="post_id" value="{{ $post->post_id }}">
                        <button type="submit" class="vote-button" style="background: none; border: none; cursor: pointer;">
                            <span class="comment-rating-button comment-like-button material-icons {{ $reply->userHasLiked($user) ? 'liked' : '' }}" data-type="like" data-comment-id="{{ $reply->comment_id }}">thumb_up</span>
                            <span class="comment-rating-count" data-type="like" data-comment-id="{{ $reply->comment_id }}">{{ $reply->likes->count() }}</span>
                        </button>
                    </form>

                    <form class="comment-vote-form" action="{{ route('comment.dislike') }}" method="POST">
                        @csrf
                        <input type="hidden" name="comment_id" value="{{ $reply->comment_id }}">
                        <input type="hidden" name="post_id" value="{{ $post->post_id }}">
                        <button type="submit" class="vote-button" style="background: none; border: none; cursor: pointer;">
                            <span class="comment-rating-button comment-dislike-button material-icons {{ $reply->userHasDisliked($user) ? 'disliked' : '' }}" data-type="dislike" data-comment-id="{{ $reply->comment_id }}">thumb_down</span>
                            <span class="comment-rating-count" data-type="dislike" data-comment-id="{{ $reply->comment_id }}">{{ $reply->dislikes->count() }}</span>
                        </button>
                    </form>
                    <button class="reply-button" data-comment-id="{{ $reply->comment_id }}">
                        <span class="material-symbols-outlined">reply_all</span>
                        Reply
                    </button>
                </div>
                <small>Posted on {{ $reply->created_at->format('F j, Y, g:i a') }}</small>

                <!-- Reply CKEditor for replies -->
                <div class="reply-editor-container" id="reply-editor-{{ $reply->comment_id }}" style="display: none;">
                    <form method="POST" action="{{ route('comment.reply', ['post_id' => $post->post_id, 'parent_comment_id' => $reply->comment_id]) }}" onsubmit="return validateReplyForm({{ $reply->comment_id }})">
                        @csrf
                        <textarea id="editor-{{ $reply->comment_id }}" name="content"></textarea>
                        <button type="submit">Send Reply</button>
                    </form>
                </div>
            </div>

            <!-- Recursively display nested replies -->
            @include('partials.comments', ['comments' => $reply->replies, 'level' => $level + 1])
        </li>
        @endforeach
    </ul>
@endif