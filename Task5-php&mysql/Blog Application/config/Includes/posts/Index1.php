<?php
require_once __DIR__ . '/includes/auth.php';

// Pagination for all posts
$pagination = paginate(
    'posts',
    5
);

// Fetch posts for current page
$stmt = $pdo->prepare("
    SELECT posts.*, users.username 
    FROM posts 
    JOIN users ON posts.user_id = users.id
    ORDER BY posts.created_at DESC
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $pagination['per_page'], PDO::PARAM_INT);
$stmt->bindValue(':offset', $pagination['offset'], PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();
?>

<?php include __DIR__ . '/includes/header.php'; ?>

<div class="jumbotron bg-light p-5 rounded-lg mb-4">
    <h1 class="display-4">Welcome to PHP Blog</h1>
    <p class="lead">A simple blog application with CRUD operations and authentication.</p>
    <hr class="my-4">
    <?php if (!is_logged_in()): ?>
        <p>Please register or login to create and manage your posts.</p>
        <a class="btn btn-primary btn-lg me-2" href="/blog-application/auth/register.php" role="button">Register</a>
        <a class="btn btn-success btn-lg" href="/blog-application/auth/login.php" role="button">Login</a>
    <?php else: ?>
        <p>Welcome back, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
        <a class="btn btn-primary btn-lg me-2" href="/blog-application/posts/" role="button">View Your Posts</a>
        <a class="btn btn-success btn-lg" href="/blog-application/posts/create.php" role="button">Create New Post</a>
    <?php endif; ?>
</div>

<!-- Search Form -->
<form action="/blog-application/posts/search.php" method="GET" class="mb-4">
    <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search posts...">
        <button type="submit" class="btn btn-primary">Search</button>
    </div>
</form>

<h2 class="mb-4">Latest Posts</h2>

<?php if (count($posts) > 0): ?>
    <div class="list-group mb-4">
        <?php foreach ($posts as $post): ?>
            <div class="list-group-item">
                <div class="d-flex justify-content-between">
                    <h4><?= htmlspecialchars($post['title']) ?></h4>
                    <?php if (is_logged_in() && $_SESSION['user_id'] == $post['user_id']): ?>
                        <div>
                            <a href="/blog-application/posts/edit.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="/blog-application/posts/delete.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </div>
                    <?php endif; ?>
                </div>
                <p class="mb-1"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <small class="text-muted">
                    Posted by <?= htmlspecialchars($post['username']) ?> on <?= date('F j, Y, g:i a', strtotime($post['created_at'])) ?>
                </small>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($pagination['total_pages'] > 1): ?>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php if ($pagination['current_page'] > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $pagination['current_page'] - 1 ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                    <li class="page-item <?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $pagination['current_page'] + 1 ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
<?php else: ?>
    <div class="alert alert-info">No posts found.</div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>