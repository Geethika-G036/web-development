<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

// Check if user is logged in, otherwise redirect to login
function require_login()
{
    if (!is_logged_in()) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        redirect('/blog-application/auth/login.php');
    }
}
?>
<?php
session_start();

// Regenerate session ID to prevent session fixation
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = true;
}

// Set security headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

// CSRF token generation and validation
function generate_csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validate_csrf_token($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Authentication functions
function require_login()
{
    if (!is_logged_in()) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        $_SESSION['message'] = 'Please login to access this page';
        $_SESSION['message_type'] = 'danger';
        redirect('/blog-application/auth/login.php');
    }
}

function require_role($required_role)
{
    require_login();

    if (!has_role($required_role)) {
        $_SESSION['message'] = 'You do not have permission to access this page';
        $_SESSION['message_type'] = 'danger';
        redirect('/blog-application/');
    }
}

function has_role($role)
{
    if (!is_logged_in()) return false;

    // Admin has access to everything
    if ($_SESSION['role'] === 'admin') return true;

    // Role hierarchy
    $hierarchy = ['admin', 'editor', 'author', 'subscriber'];
    $user_level = array_search($_SESSION['role'], $hierarchy);
    $required_level = array_search($role, $hierarchy);

    return $user_level <= $required_level;
}
?>