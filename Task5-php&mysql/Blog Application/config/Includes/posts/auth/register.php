<?php
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $_SESSION['message'] = 'Username and password are required';
        $_SESSION['message_type'] = 'danger';
    } else {
        // Check if username exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = 'Username already taken';
            $_SESSION['message_type'] = 'danger';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashed_password]);

            $_SESSION['message'] = 'Registration successful! Please login';
            redirect('/blog-application/auth/login.php');
        }
    }
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h2>Register</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
            <p class="mt-3">Already have an account? <a href="/blog-application/auth/login.php">Login here</a></p>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?><?php
                                                        require_once __DIR__ . '/../includes/auth.php';
                                                        require_once __DIR__ . '/../includes/validation.php';

                                                        $errors = [];

                                                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                            // Validate CSRF token
                                                            if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
                                                                $errors[] = 'Invalid CSRF token';
                                                            }

                                                            $username = sanitize_input($_POST['username'] ?? '');
                                                            $password = $_POST['password'] ?? '';

                                                            // Validate inputs
                                                            if ($error = validate_username($username)) {
                                                                $errors[] = $error;
                                                            }
                                                            if ($error = validate_password($password)) {
                                                                $errors[] = $error;
                                                            }

                                                            if (empty($errors)) {
                                                                try {
                                                                    // Check if username exists
                                                                    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
                                                                    $stmt->execute([$username]);

                                                                    if ($stmt->rowCount() > 0) {
                                                                        $errors[] = 'Username already taken';
                                                                    } else {
                                                                        // Hash password
                                                                        $hashed_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

                                                                        // Insert new user with default role
                                                                        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'author')");
                                                                        $stmt->execute([$username, $hashed_password]);

                                                                        $_SESSION['message'] = 'Registration successful! Please login';
                                                                        $_SESSION['message_type'] = 'success';
                                                                        redirect('/blog-application/auth/login.php');
                                                                    }
                                                                } catch (PDOException $e) {
                                                                    error_log("Registration error: " . $e->getMessage());
                                                                    $errors[] = 'Registration failed. Please try again.';
                                                                }
                                                            }
                                                        }

                                                        $csrf_token = generate_csrf_token();
                                                        ?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h2>Register</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" id="registerForm">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username"
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required
                    minlength="3" maxlength="50" pattern="[a-zA-Z0-9_]+">
                <div class="invalid-feedback">Username must be 3-50 characters (letters, numbers, underscores)</div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required
                    minlength="8" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$">
                <div class="invalid-feedback">Password must be at least 8 characters with uppercase, lowercase and numbers</div>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
            <p class="mt-3">Already have an account? <a href="/blog-application/auth/login.php">Login here</a></p>
        </form>
    </div>
</div>

<script>
    // Client-side validation
    document.getElementById('registerForm').addEventListener('submit', function(event) {
        const form = event.target;
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>