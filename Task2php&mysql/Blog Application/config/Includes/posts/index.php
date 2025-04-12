<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();

$stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$posts = $stmt->fetchAll();
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>My Posts</h1>
    <a href="/blog-application/posts/create.php" class="btn btn-success">Create New Post</a>
</div>

<?php if (count($posts) > 0): ?>
    <div class="list-group">
        <?php foreach ($posts as $post): ?>
            <div class="list-group-item">
                <div class="d-flex justify-content-between">
                    <h4><?= htmlspecialchars($post['title']) ?></h4>
                    <div>
                        <a href="/blog-application/posts/edit.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="/blog-application/posts/delete.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </div>
                </div>
                <p class="mb-1"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <small class="text-muted">Posted on <?= date('F j, Y, g:i a', strtotime($post['created_at'])) ?></small>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="alert alert-info">No posts found. Create your first post!</div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>