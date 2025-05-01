<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Blog - <?= $title ?? 'Home' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/blog-application/assets/css/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="/blog-application/">
                <i class="bi bi-journal-text"></i> PHP Blog
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (is_logged_in()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/blog-application/posts/">
                                <i class="bi bi-journal"></i> My Posts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/blog-application/posts/create.php">
                                <i class="bi bi-plus-circle"></i> Create Post
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/blog-application/auth/logout.php">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/blog-application/auth/login.php">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/blog-application/auth/register.php">
                                <i class="bi bi-person-plus"></i> Register
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php
        if (isset($_SESSION['message'])) {
            echo display_message($_SESSION['message'], $_SESSION['message_type'] ?? 'success');
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
        ?>