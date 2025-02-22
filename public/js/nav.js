document.addEventListener('DOMContentLoaded', function () {
    //Initialize variables for side bar
    const sidebar = document.getElementById('sidebar');
    const toggleSidebarBtn = document.getElementById('toggleSidebar');
    const menuIcon = document.getElementById('menuIcon');
    const mainContent = document.getElementById('main-content');

    // Initialize sidebar and main content with expanded class
    sidebar.classList.add('minimized');
    mainContent.classList.add('minimized');

    //Menu icon logic
    menuIcon.addEventListener('click', function () {
        if (sidebar.classList.contains('minimized')) {
            sidebar.classList.remove('minimized');
            sidebar.classList.add('expanded');
            mainContent.classList.remove('minimized');
            mainContent.classList.add('expanded');
        } else {
            sidebar.classList.add('minimized');
            sidebar.classList.remove('expanded');
            mainContent.classList.add('minimized');
            mainContent.classList.remove('expanded');
        }
    });

    //Side bar logic
    toggleSidebarBtn.addEventListener('click', function () {
        if (sidebar.classList.contains('minimized')) {
            sidebar.classList.remove('minimized');
            sidebar.classList.add('expanded');
            mainContent.classList.remove('minimized');
            mainContent.classList.add('expanded');
        } else {
            sidebar.classList.add('minimized');
            sidebar.classList.remove('expanded');
            mainContent.classList.add('minimized');
            mainContent.classList.remove('expanded');
        }
    });

    // Close sidebar when clicking outside of it
    document.addEventListener('click', function (event) {
        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickOnMenuIcon = menuIcon.contains(event.target);

        if (!isClickInsideSidebar && !isClickOnMenuIcon) {
            sidebar.classList.add('minimized');
            sidebar.classList.remove('expanded');
            mainContent.classList.add('minimized');
            mainContent.classList.remove('expanded');
        }
    });
});