@if($comments->isNotEmpty())
    <ul class="comments-list">
        @foreach($comments as $reply)
        <div class="reply-indent"  style="margin-left: 20px;">
            <li class="comment-item">
                <p>{!! $reply->content !!}</p>
                <small>Posted on {{ $reply->created_at->format('F j, Y, g:i a') }}</small>
                <button class="reply-button" data-comment-id="{{ $reply->comment_id }}">Reply</button>

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
