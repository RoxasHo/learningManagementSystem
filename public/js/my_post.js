document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(event) {
        if (!confirm('Are you sure you want to delete this post?')) {
            event.preventDefault(); // Prevent form submission if not confirmed
        }
    });
});

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
});