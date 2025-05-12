<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();

// Pagination
$pagination = paginate(
    'posts',
    5,
    'user_id = ?',
    [$_SESSION['user_id']]
);

// Fetch posts for current page
$stmt = $pdo->prepare("
    SELECT * FROM posts 
    WHERE user_id = ?
    ORDER BY created_at DESC
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $pagination['per_page'], PDO::PARAM_INT);
$stmt->bindValue(':offset', $pagination['offset'], PDO::PARAM_INT);
$stmt->execute([$_SESSION['user_id']]);
$posts = $stmt->fetchAll();
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>My Posts</h1>
    <div>
        <a href="/blog-application/posts/create.php" class="btn btn-success">Create New Post</a>
    </div>
</div>

<!-- Search Form -->
<form action="/blog-application/posts/search.php" method="GET" class="mb-4">
    <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search your posts...">
        <button type="submit" class="btn btn-primary">Search</button>
    </div>
</form>

<?php if (count($posts) > 0): ?>
    <div class="list-group mb-4">
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
    <div class="alert alert-info">No posts found. Create your first post!</div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?><?php
                                                        require_once __DIR__ . '/../../includes/auth.php';
                                                        require_role('admin');

                                                        // Get all users for admin management
                                                        $stmt = $pdo->query("SELECT id, username, role, created_at FROM users ORDER BY created_at DESC");
                                                        $users = $stmt->fetchAll();

                                                        // Get all posts for admin management
                                                        $stmt = $pdo->query("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC");
                                                        $posts = $stmt->fetchAll();

                                                        $csrf_token = generate_csrf_token();
                                                        ?>

<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="container">
    <h1 class="my-4">Admin Dashboard</h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h2>User Management</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['username']) ?></td>
                                        <td>
                                            <form method="POST" action="update_role.php" class="d-inline">
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                                    <option value="editor" <?= $user['role'] === 'editor' ? 'selected' : '' ?>>Editor</option>
                                                    <option value="author" <?= $user['role'] === 'author' ? 'selected' : '' ?>>Author</option>
                                                    <option value="subscriber" <?= $user['role'] === 'subscriber' ? 'selected' : '' ?>>Subscriber</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                                        <td>
                                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                <form method="POST" action="delete_user.php" class="d-inline">
                                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2>Post Management</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($posts as $post): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($post['title']) ?></td>
                                        <td><?= htmlspecialchars($post['username']) ?></td>
                                        <td><?= date('M j, Y', strtotime($post['created_at'])) ?></td>
                                        <td>
                                            <a href="/blog-application/posts/edit.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <form method="POST" action="delete_post.php" class="d-inline">
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>