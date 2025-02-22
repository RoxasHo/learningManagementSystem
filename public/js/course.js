document.addEventListener('DOMContentLoaded', function () {
    const courseContainers = document.querySelectorAll('.course-container');
    
    // Ensure containers are visible upon initial page load
    checkCourseVisibility();

    // Update lastScrollY on scroll
    window.addEventListener('scroll', () => {
        checkCourseVisibility(); // Check visibility on scroll
    });

    window.addEventListener('resize', checkCourseVisibility); // Check visibility on resize

    function checkCourseVisibility() {
        const windowHeight = window.innerHeight;
        const visibilityThreshold = 0.1; // Adjust the percentage of the container visible

        courseContainers.forEach(container => {
            const rect = container.getBoundingClientRect();
            // Check if a certain percentage of the container is visible
            const isVisible = rect.top < windowHeight && rect.bottom > windowHeight * visibilityThreshold;

            if (isVisible) {
                container.classList.add('visible'); // Slide in
            } else {
                container.classList.remove('visible'); // Slide out
            }
        });
    }
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

document.addEventListener('DOMContentLoaded', function () {
    // Select the search form and the category filter
    const searchForm = document.getElementById('search-form');
    const categoryFilter = document.getElementById('category-filter'); // Assuming this is your filter dropdown

    // Event listener for the search form
    if (searchForm) {
        searchForm.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent default form submission
            const searchValue = document.getElementById('search-input').value.trim();

            // Perform search operation, e.g., filtering the displayed courses based on searchValue
            filterCourses(searchValue);
        });
    }

    // Event listener for the category filter
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function () {
            const selectedCategory = categoryFilter.value;
            // Filter courses based on the selected category
            filterCoursesByCategory(selectedCategory);
        });
    }

    // Function to filter courses based on the search input
    function filterCourses(searchValue) {
        const courseContainers = document.querySelectorAll('.course-container');
        courseContainers.forEach(container => {
            const courseName = container.querySelector('h3').textContent.toLowerCase();
            if (courseName.includes(searchValue.toLowerCase())) {
                container.style.display = ''; // Show the course container
            } else {
                container.style.display = 'none'; // Hide the course container
            }
        });
    }

    // Function to filter courses based on selected category
    function filterCoursesByCategory(categoryId) {
        const courseContainers = document.querySelectorAll('.course-container');
        courseContainers.forEach(container => {
            // Get the data-category-ids attribute
            const categoryIds = container.dataset.categoryIds;
            
            // Ensure categoryIds is defined before attempting to split
            if (categoryIds) {
                const idsArray = categoryIds.split(',');
                if (categoryId === 'all' || idsArray.includes(categoryId)) {
                    container.style.display = ''; // Show the course container
                } else {
                    container.style.display = 'none'; // Hide the course container
                }
            } else {
                container.style.display = 'none'; // Hide the container if no category IDs
            }
        });
    }

    // Add more functions for sorting if needed
    // For example, a function to sort courses by difficulty or enrollment
});



