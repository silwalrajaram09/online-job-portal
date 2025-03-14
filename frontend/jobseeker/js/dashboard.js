// Sidebar Toggle Functionality
 function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('collapsed');
        }

       // Add event listeners to sidebar menu items
document.querySelectorAll('.sidebar-menu a').forEach(item => {
    item.addEventListener('click', function (e) {
        const page = this.getAttribute('data-page'); // Get the data-page attribute
        
        // If no data-page attribute exists (e.g., for Dashboard), allow the page to refresh
        if (!page) return;

        e.preventDefault(); // Prevent default link behavior for other links

        // Fetch content dynamically for links with data-page attribute
        fetch(page)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not OK');
                return response.text(); // Parse response as text (HTML)
            })
            .then(html => {
                document.getElementById('content-container').innerHTML = html; // Inject content
            })
            .catch(error => {
                console.error('Error loading page:', error);
                document.getElementById('content-container').innerHTML = '<p>Error loading content.</p>';
            });
    });
});
