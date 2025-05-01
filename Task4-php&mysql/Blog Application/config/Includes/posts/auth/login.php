<?php
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $_SESSION['message'] = 'Username and password are required';
        $_SESSION['message_type'] = 'danger';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            $redirect_url = $_SESSION['redirect_url'] ?? '/blog-application/';
            unset($_SESSION['redirect_url']);
            
            $_SESSION['message'] = 'Login successful!';
            redirect($redirect_url);
        } else {
            $_SESSION['message'] = 'Invalid username or password';
            $_SESSION['message_type'] = 'danger';
        }
    }
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h2>Login</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <p class="mt-3">Don't have an account? <a href="/blog-application/auth/register.php">Register here</a></p>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>