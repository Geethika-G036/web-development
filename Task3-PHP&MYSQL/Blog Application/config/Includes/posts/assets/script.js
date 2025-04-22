// Add any JavaScript functionality you need
document.addEventListener('DOMContentLoaded', function () {
    // Add active class to current nav link
    const currentPage = window.location.pathname;
    document.querySelectorAll('.nav-link').forEach(link => {
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('active');
        }
    });

    // Auto-focus search input when on search page
    if (currentPage.includes('search.php') {
        const searchInput = document.querySelector('input[name="q"]');
        if (searchInput) {
            searchInput.focus();
            // Highlight search terms in results
            const searchQuery = new URLSearchParams(window.location.search).get('q');
            if (searchQuery) {
                const posts = document.querySelectorAll('.list-group-item');
                posts.forEach(post => {
                    const html = post.innerHTML;
                    const highlighted = html.replace(
                        new RegExp(searchQuery, 'gi'),
                        match => `<span class="bg-warning">${match}</span>`
                    );
                    post.innerHTML = highlighted;
                });
            }
        }
    }
});