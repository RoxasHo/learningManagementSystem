document.addEventListener('DOMContentLoaded', function () {
    const starRatings = document.querySelectorAll('.star-rating');

    starRatings.forEach(starRating => {
        const rating = parseFloat(starRating.getAttribute('data-rating'));
        const fullStars = Math.floor(rating); // Number of full stars
        const halfStar = rating % 1 >= 0.5; // Is there a half star
        const emptyStars = 5 - fullStars - (halfStar ? 1 : 0); // Number of empty stars

        // Add full stars
        for (let i = 0; i < fullStars; i++) {
            const fullStarElement = document.createElement('i');
            fullStarElement.classList.add('fas', 'fa-star');
            starRating.appendChild(fullStarElement);
        }

        // Add half star
        if (halfStar) {
            const halfStarElement = document.createElement('i');
            halfStarElement.classList.add('fas', 'fa-star-half-alt');
            starRating.appendChild(halfStarElement);
        }

        // Add empty stars
        for (let i = 0; i < emptyStars; i++) {
            const emptyStarElement = document.createElement('i');
            emptyStarElement.classList.add('far', 'fa-star');
            starRating.appendChild(emptyStarElement);
        }
    });
    
});

document.addEventListener('DOMContentLoaded', function () {
    const rateCourseButton = document.getElementById('rateCourseButton');
    const ratingModal = document.getElementById('ratingModal');
    const closeModal = ratingModal ? ratingModal.querySelector('.close') : null;
    const starRating = document.getElementById('starRating');
    let selectedRating = 0;

    if (!rateCourseButton) {
        console.error('Rate course button not found!');
        return; // Stop execution if button does not exist
    }

    const courseId = rateCourseButton.dataset.courseId;

    // Open the modal when the button is clicked
    rateCourseButton.onclick = function () {
        ratingModal.style.display = 'block';
    };

    // Close the modal when the close button is clicked
    if (closeModal) {
        closeModal.onclick = function () {
            ratingModal.style.display = 'none';
        };
    }

    // Close the modal when clicking outside of the modal
    window.onclick = function (event) {
        if (event.target == ratingModal) {
            ratingModal.style.display = 'none';
        }
    };

    // Handle star clicks
    if (starRating) {
        starRating.querySelectorAll('span').forEach(function (star) {
            star.onclick = function () {
                selectedRating = this.dataset.value;
                starRating.querySelectorAll('span').forEach(function (s) {
                    s.classList.remove('selected');
                });
                for (let i = 0; i < selectedRating; i++) {
                    starRating.children[i].classList.add('selected');
                }
            };
        });
    } else {
        console.error('Star rating element not found!');
    }

    // Handle rating submission
    document.getElementById('submitRating').onclick = function () {
        if (selectedRating > 0) {
            submitRating(courseId, selectedRating);
            ratingModal.style.display = 'none'; // Close modal after submission
        } else {
            alert('Please select a rating before submitting.');
        }
    };
    

    function submitRating(courseId, rating) {
        fetch('/enrollments/rate', { // Update with your actual route for storing ratings
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ course_id: courseId, rating: rating })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Rating submitted successfully!');
            } else {
                alert('Failed to submit rating.');
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }

    console.log('Star Rating:', starRating);
    console.log('Rate Course Button:', rateCourseButton);

});
