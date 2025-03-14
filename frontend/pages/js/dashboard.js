
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('collapsed');
}
// Initialize when page is first loaded
toggleSidebar(); 

function loadPage() {
    $('.sidebar-menu a').on('click', function (e) {
        const page = $(this).attr('data-page'); // Get the data-page attribute
        const href = $(this).attr('href'); // Get the href attribute

        if (!page || !href) return;

        e.preventDefault(); // Prevent default link behavior

        $('#loading').show(); // Show loading indicator

        $.ajax({
            url: page, // Load content from the data-page value
            type: 'GET',
            success: function (data) {
                $('#content-container').html(data); // Inject the dynamic content
                // Update the browser's URL using history.pushState
                history.pushState({ page: page }, '', href); // Use href for the URL in the browser
            },
            error: function () {
                alert('Failed to load content.');
            },
            complete: function () {
                $('#loading').hide(); // Hide loading indicator
            }
        });
    });
}

 // Handle browser back/forward navigation
window.onpopstate = function (event) {
    if (event.state && event.state.page) {
        const page = event.state.page;

        $('#loading').show(); // Show loading indicator

        $.ajax({
            url: page,
            type: 'GET',
            success: function (data) {
                $('#content-container').html(data); // Inject the dynamic content
            },
            error: function () {
                alert('Failed to load content.');
            },
            complete: function () {
                $('#loading').hide(); // Hide loading indicator
            }
        });
    }
};

 // Load the correct page on initial load or refresh
function loadInitialPage() {
    const currentUrl = window.location.pathname; // Get the current path (e.g., "/dashboard.php")
    const matchingLink = $('.sidebar-menu a[href="' + currentUrl + '"]'); // Match the current URL with a sidebar link
    const currentPage = matchingLink.attr('data-page'); // Get the data-page for the current link

    if (currentPage) {
        $('#loading').show(); // Show loading indicator

        $.ajax({
            url: currentPage,
            type: 'GET',
            success: function (data) {
                $('#content-container').html(data); // Inject the content
                // Set the initial history state
                history.replaceState({ page: currentPage }, '', currentUrl);
            },
            error: function () {
                alert('Failed to load content.');
            },
            complete: function () {
                $('#loading').hide(); // Hide loading indicator
            }
        });
    }
}

$(document).ready(function () {
    loadPage(); // Set up dynamic navigation
    loadInitialPage(); // Load the current page on refresh
});

