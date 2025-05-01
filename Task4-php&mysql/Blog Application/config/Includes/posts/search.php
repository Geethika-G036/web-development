<?php
require_once __DIR__ . '/../includes/auth.php';

$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';

// Pagination for search results
$pagination = paginate(
    'posts',
    5,
    "title LIKE :search OR content LIKE :search",
    ['search' => "%$search_query%"]
);

// Fetch search results
$stmt = $pdo->prepare("
    SELECT posts.*, users.username 
    FROM posts 
    JOIN users ON posts.user_id = users.id
    WHERE posts.title LIKE :search OR posts.content LIKE :search
    ORDER BY posts.created_at DESC
    LIMIT :limit OFFSET :offset
");

$stmt->bindValue(':search', "%$search_query%");
$stmt->bindValue(':limit', $pagination['per_page'], PDO::PARAM_INT);
$stmt->bindValue(':offset', $pagination['offset'], PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Search Results for "<?= htmlspecialchars($search_query) ?>"</h1>
    <a href="/blog-application/posts/" class="btn btn-primary">Back to All Posts</a>
</div>

<!-- Search Form -->
<form action="/blog-application/posts/search.php" method="GET" class="mb-4">
    <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search posts..." value="<?= htmlspecialchars($search_query) ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </div>
</form>

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
                        <a class="page-link" href="?q=<?= urlencode($search_query) ?>&page=<?= $pagination['current_page'] - 1 ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                    <li class="page-item <?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                        <a class="page-link" href="?q=<?= urlencode($search_query) ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <li class="page-item">
                        <a class="page-link" href="?q=<?= urlencode($search_query) ?>&page=<?= $pagination['current_page'] + 1 ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
<?php else: ?>
    <div class="alert alert-info">No posts found matching your search criteria.</div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>