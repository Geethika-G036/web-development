// DOM Elements
const postContent = document.getElementById('post-content');
const commentsContainer = document.getElementById('comments-container');
const commentForm = document.querySelector('.comment-form');

// Get post ID from URL
const urlParams = new URLSearchParams(window.location.search);
const postId = urlParams.get('id');

// Load single post
async function loadPost() {
    if (!postId) {
        postContent.innerHTML = '<p class="error">Post not found.</p>';
        return;
    }

    try {
        const response = await fetch('posts/posts.json');
        const posts = await response.json();
        const post = posts.find(p => p.id == postId);

        if (!post) {
            postContent.innerHTML = '<p class="error">Post not found.</p>';
            return;
        }

        // Render post
        postContent.innerHTML = `
            <div class="post-header">
                <span class="post-category">${post.category}</span>
                <h1 class="post-title">${post.title}</h1>
                <div class="post-meta">
                    <span class="post-date">${post.date}</span>
                    <span class="post-read-time">${post.readTime} min read</span>
                </div>
            </div>
            <div class="post-image">
                <img src="${post.image}" alt="${post.title}" loading="lazy">
            </div>
            <div class="post-content">
                ${post.content}
            </div>
        `;

        // Load comments (simulated)
        loadComments();
    } catch (error) {
        console.error('Error loading post:', error);
        postContent.innerHTML = '<p class="error">Unable to load post. Please try again later.</p>';
    }
}

// Load comments (simulated)
function loadComments() {
    const comments = [
        {
            id: 1,
            author: 'John Doe',
            date: 'May 20, 2025',
            text: 'Great post! Very informative and well written.'
        },
        {
            id: 2,
            author: 'Jane Smith',
            date: 'May 22, 2025',
            text: 'Thanks for sharing this. Helped me solve a problem I was having.'
        }
    ];

    commentsContainer.innerHTML = comments.map(comment => `
        <div class="comment">
            <div class="comment-author">${comment.author}</div>
            <div class="comment-date">${comment.date}</div>
            <div class="comment-text">${comment.text}</div>
        </div>
    `).join('');
}

// Handle comment submission
commentForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const name = document.getElementById('name').value;
    const commentText = document.getElementById('comment').value;

    if (!name || !commentText) return;

    // Add new comment (in a real app, this would send to a server)
    const newComment = {
        id: Date.now(),
        author: name,
        date: new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }),
        text: commentText
    };

    const commentElement = document.createElement('div');
    commentElement.className = 'comment';
    commentElement.innerHTML = `
        <div class="comment-author">${newComment.author}</div>
        <div class="comment-date">${newComment.date}</div>
        <div class="comment-text">${newComment.text}</div>
    `;

    commentsContainer.prepend(commentElement);

    // Reset form
    commentForm.reset();
});

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    if (postContent) {
        loadPost();
    }
});