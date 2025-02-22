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
        const commentId = this.getAttribute('data-comment-id') || (this.closest('.comment-item') ? this.closest('.comment-item').querySelector('.comment-id').value : null);
        const postUserId = this.getAttribute('data-post-user-id');
        const currentUserId = this.getAttribute('data-current-user-id');

        // Check if the report is for a post (not a comment)
        if (!commentId && postUserId === currentUserId) {
            // If the logged-in user is the post author, show the warning
            document.getElementById('authorWarning').style.display = 'block';  // Show the warning message
            document.getElementById('reportModal').style.display = 'none';    // Ensure the report modal is hidden
            return;  // Stop further execution to prevent opening the modal
        }

        // Continue with setting postId and commentId if the user is not the post author
        document.getElementById('postId').value = postId;
        document.getElementById('commentId').value = commentId || ''; // Set commentId if available

        // Open the report modal
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
