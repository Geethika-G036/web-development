<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();

// Get the post to edit
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
$stmt->execute([$_GET['id'], $_SESSION['user_id']]);
$post = $stmt->fetch();

if (!$post) {
    $_SESSION['message'] = 'Post not found or access denied';
    $_SESSION['message_type'] = 'danger';
    redirect('/blog-application/posts/');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $_SESSION['message'] = 'Title and content are required';
        $_SESSION['message_type'] = 'danger';
    } else {
        $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $stmt->execute([$title, $content, $post['id']]);
        
        $_SESSION['message'] = 'Post updated successfully!';
        redirect('/blog-application/posts/');
    }
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <h2>Edit Post</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" rows="6" required><?= htmlspecialchars($post['content']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Post</button>
            <a href="/blog-application/posts/" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
