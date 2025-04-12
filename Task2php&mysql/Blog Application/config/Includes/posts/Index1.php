<?php
require_once __DIR__ . '/includes/auth.php';

// Get all posts for the homepage
$stmt = $pdo->query("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY created_at DESC");
$posts = $stmt->fetchAll();
?>

<?php include __DIR__ . '/includes/header.php'; ?>

<div class="jumbotron">
    <h1 class="display-4">Welcome to PHP Blog</h1>
    <p class="lead">A simple blog application with CRUD operations and authentication.</p>
    <hr class="my-4">
    <?php if (!is_logged_in()): ?>
        <p>Please register or login to create and manage your posts.</p>
        <a class="btn btn-primary btn-lg" href="/blog-application/auth/register.php" role="button">Register</a>
        <a class="btn btn-success btn-lg" href="/blog-application/auth/login.php" role="button">Login</a>
    <?php else: ?>
        <p>Welcome back, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
        <a class="btn btn-primary btn-lg" href="/blog-application/posts/" role="button">View Your Posts</a>
    <?php endif; ?>
</div>

<h2>Latest Posts</h2>

<?php if (count($posts) > 0): ?>
    <div class="list-group">
        <?php foreach ($posts as $post): ?>
            <div class="list-group-item">
                <h4><?= htmlspecialchars($post['title']) ?></h4>
                <p class="mb-1"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <small class="text-muted">
                    Posted by <?= htmlspecialchars($post['username']) ?> on <?= date('F j, Y, g:i a', strtotime($post['created_at'])) ?>
                </small>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="alert alert-info">No posts found.</div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
