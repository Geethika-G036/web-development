<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/blog-application/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/blog-application/">PHP Blog</a>
            <div class="navbar-nav">
                <?php if (is_logged_in()): ?>
                    <a class="nav-link" href="/blog-application/posts/create.php">Create Post</a>
                    <a class="nav-link" href="/blog-application/auth/logout.php">Logout</a>
                <?php else: ?>
                    <a class="nav-link" href="/blog-application/auth/login.php">Login</a>
                    <a class="nav-link" href="/blog-application/auth/register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php
        if (isset($_SESSION['message'])) {
            echo display_message($_SESSION['message'], $_SESSION['message_type'] ?? 'success');
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
        ?>