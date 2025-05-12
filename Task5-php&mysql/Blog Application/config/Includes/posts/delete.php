<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();

if (isset($_GET['id'])) {
    // Verify the post belongs to the logged-in user
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET['id'], $_SESSION['user_id']]);
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['message'] = 'Post deleted successfully!';
    } else {
        $_SESSION['message'] = 'Post not found or access denied';
        $_SESSION['message_type'] = 'danger';
    }
}

redirect('/blog-application/posts/');
?>
