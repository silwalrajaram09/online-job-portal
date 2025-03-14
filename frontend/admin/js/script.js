// function loadContent(page) {
//     const contentArea = document.getElementById('content-area');
//     const xhr = new XMLHttpRequest();

//     xhr.open('GET', page, true);
//     xhr.onload = function() {
//         if (this.status === 200) {
//             contentArea.innerHTML = this.responseText;
//         } else {
//             contentArea.innerHTML = '<p>Error loading content.</p>';
//         }
//     };
//     xhr.send();
// }
// $(document).ready(function () {
//     function loadAdminPage(url, addToHistory = true) {
//         $.ajax({
//             url: url,
//             type: "GET",
//             dataType: "html",
//             success: function (data) {
//                 $("#admin-content").html(data); // Load page inside content div

//                 if (addToHistory) {
//                     history.pushState({ path: url }, "", url); // Update browser URL
//                 }
//             },
//             error: function () {
//                 $("#admin-content").html("<p>Page not found.</p>");
//             }
//         });
//     }

//     // Sidebar Click Handling
//     $(".sidebar-menu a").on("click", function (e) {
//         e.preventDefault(); // Prevent full page reload

//         let pageUrl = $(this).attr("href"); // Get the link
//         loadAdminPage(pageUrl);
//     });

//     // Handle Browser Back/Forward Buttons
//     window.onpopstate = function (event) {
//         if (event.state && event.state.path) {
//             loadAdminPage(event.state.path, false);
//         }
//     };

//     // Load Correct Page on Refresh
//     let currentPath = window.location.pathname;
//     if (currentPath !== "/ojs/admin/dashboard") {
//         loadAdminPage(currentPath, false);
//     }
// });
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('collapsed');
}
// Initialize when page is first loaded
toggleSidebar();

$(document).ready(function () {
    function loadAdminPage(page, addToHistory = true) {
        if (!page) {
            console.log("Page is missing or undefined!");
            return;
        }

        console.log("Loading Page:", page); // Debugging

        $("#content-container").html("<p>Loading...</p>"); // Show loading state

        $.ajax({
            url:  page , // Ensure correct file path
            type: "GET",
            dataType: "html",
            success: function (data) {
                console.log("Page loaded successfully:", page);
                $("#content-container").html(data); // Load content here

                if (addToHistory) {
                    history.pushState({ path: page }, "", "?page=" + page);
                }
            },
            error: function (xhr, status, error) {
                console.log("AJAX Error:", error);
                $("#content-container").html("<p style='color:red;'>Page not found.</p>");
            }
        });
    }

    // Sidebar Click Handling
    $(document).on("click", ".sidebar-menu a", function (e) {
        e.preventDefault();
        let page = $(this).data("page");

        console.log("Clicked page:", page); // Debugging
        if (page) {
            loadAdminPage(page);
        } 
    });

    // Handle Browser Back/Forward Buttons
    window.onpopstate = function (event) {
        if (event.state && event.state.path) {
            loadAdminPage(event.state.path, false);
        }
    };

    // Load Correct Page on Refresh
    let urlParams = new URLSearchParams(window.location.search);
    let currentPage = urlParams.get("page");
    if (currentPage) {
        loadAdminPage(currentPage, false);
    }
});


