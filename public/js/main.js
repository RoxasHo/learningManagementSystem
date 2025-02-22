document.addEventListener('DOMContentLoaded', function () {
    const fadeImage = document.querySelector('.fade-image');
    const textOverlay = document.querySelector('.text-overlay');
    const courseContainers = document.querySelectorAll('.course-container');

    fadeImage.classList.add('visible'); // Fade in image on load

    let lastScrollY = window.scrollY;
    const slideThreshold = 100; // Set your desired threshold in pixels
    const visibilityThreshold = 0.6; // Percentage of the container that needs to be visible

    window.addEventListener('scroll', () => {
        const currentScrollY = window.scrollY;

        /* Fade out image
        if (currentScrollY > lastScrollY) {
            fadeImage.style.opacity = Math.max(0, 1 - (currentScrollY / 50)); // Adjust for faster fade-out
        } else {
            fadeImage.style.opacity = Math.min(1, (1 - (currentScrollY / 50))); // Fade back in
        }*/

        // Fade out image
        fadeImage.style.opacity = Math.max(0, 1 - (currentScrollY / 100)); // Adjust for slower fade

        // Control text overlay slide-out based on threshold
        if (currentScrollY > lastScrollY && currentScrollY > slideThreshold) {
            textOverlay.style.animation = 'slide-out 1s ease forwards'; // Slide out
        } else if (currentScrollY < lastScrollY && currentScrollY < slideThreshold) {
            textOverlay.style.animation = 'slide-in 1s ease forwards'; // Slide in
        }

        lastScrollY = currentScrollY; // Update last scroll position
        checkCourseVisibility();
    });

    function checkCourseVisibility() {
        const windowHeight = window.innerHeight;

        courseContainers.forEach(container => {
            const rect = container.getBoundingClientRect();
            // Check if a certain percentage of the container is visible
            const isVisible = rect.top < windowHeight && rect.bottom > windowHeight * (1 - visibilityThreshold);

            if (isVisible) {
                container.classList.add('visible'); // Slide in
            } else {
                container.classList.remove('visible'); // Slide out
            }
        });
    }

    window.addEventListener('resize', checkCourseVisibility); // Check visibility on resize
    checkCourseVisibility(); // Initial check when page loads
});

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