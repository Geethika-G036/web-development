<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $_SESSION['message'] = 'Title and content are required';
        $_SESSION['message_type'] = 'danger';
    } else {
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)");
        $stmt->execute([$title, $content, $_SESSION['user_id']]);

        $_SESSION['message'] = 'Post created successfully!';
        redirect('/blog-application/posts/');
    }
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <h2>Create New Post</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" rows="6" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Create Post</button>
            <a href="/blog-application/posts/" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/validation.php';
require_role('author');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid CSRF token';
    }

    $title = sanitize_input($_POST['title'] ?? '');
    $content = sanitize_input($_POST['content'] ?? '');

    // Validate inputs
    if ($error = validate_post_title($title)) {
        $errors[] = $error;
    }
    if ($error = validate_post_content($content)) {
        $errors[] = $error;
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)");
            $stmt->execute([$title, $content, $_SESSION['user_id']]);

            $_SESSION['message'] = 'Post created successfully!';
            $_SESSION['message_type'] = 'success';
            redirect('/blog-application/posts/');
        } catch (PDOException $e) {
            error_log("Post creation error: " . $e->getMessage());
            $errors[] = 'Failed to create post. Please try again.';
        }
    }
}

$csrf_token = generate_csrf_token();
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <h2>Create New Post</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" id="postForm">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title"
                    value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required
                    maxlength="255">
                <div class="invalid-feedback">Title is required (max 255 characters)</div>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" rows="6" required
                    maxlength="10000"><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
                <div class="invalid-feedback">Content is required (max 10,000 characters)</div>
            </div>

            <button type="submit" class="btn btn-primary">Create Post</button>
            <a href="/blog-application/posts/" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script>
    // Client-side validation
    document.getElementById('postForm').addEventListener('submit', function(event) {
        const form = event.target;
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>