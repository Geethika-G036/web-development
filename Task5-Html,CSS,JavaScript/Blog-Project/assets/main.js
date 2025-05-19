// DOM Elements
const postsContainer = document.getElementById('posts-container');
const menuToggle = document.querySelector('.menu-toggle');
const navList = document.querySelector('.nav-list');

// Toggle mobile menu
menuToggle.addEventListener('click', () => {
    navList.classList.toggle('active');
    menuToggle.classList.toggle('active');
});

// Load posts from JSON file
async function loadPosts() {
    try {
        const response = await fetch('posts/posts.json');
        const posts = await response.json();

        // Display only the first 6 posts on homepage
        const recentPosts = posts.slice(0, 6);

        // Clear loading state
        postsContainer.innerHTML = '';

        // Render each post
        recentPosts.forEach(post => {
            const postElement = document.createElement('article');
            postElement.className = 'post-card';
            postElement.innerHTML = `
                <div class="post-image">
                    <img src="${post.image}" alt="${post.title}" loading="lazy">
                </div>
                <div class="post-content">
                    <span class="post-category">${post.category}</span>
                    <h2><a href="post.html?id=${post.id}">${post.title}</a></h2>
                    <p class="post-excerpt">${post.excerpt}</p>
                    <div class="post-meta">
                        <span class="post-date">${post.date}</span>
                        <span class="post-read-time">${post.readTime} min read</span>
                    </div>
                </div>
            `;
            postsContainer.appendChild(postElement);
        });
    } catch (error) {
        console.error('Error loading posts:', error);
        postsContainer.innerHTML = '<p class="error">Unable to load posts. Please try again later.</p>';
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    if (postsContainer) {
        loadPosts();
    }
});