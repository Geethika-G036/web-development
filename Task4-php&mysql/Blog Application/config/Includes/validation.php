<?php
function validate_username($username)
{
    $username = trim($username);
    if (empty($username)) {
        return 'Username is required';
    }
    if (strlen($username) < 3 || strlen($username) > 50) {
        return 'Username must be between 3 and 50 characters';
    }
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        return 'Username can only contain letters, numbers, and underscores';
    }
    return null;
}

function validate_password($password)
{
    if (empty($password)) {
        return 'Password is required';
    }
    if (strlen($password) < 8) {
        return 'Password must be at least 8 characters';
    }
    if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        return 'Password must contain uppercase, lowercase letters and numbers';
    }
    return null;
}

function validate_post_title($title)
{
    $title = trim($title);
    if (empty($title)) {
        return 'Title is required';
    }
    if (strlen($title) > 255) {
        return 'Title cannot exceed 255 characters';
    }
    return null;
}

function validate_post_content($content)
{
    $content = trim($content);
    if (empty($content)) {
        return 'Content is required';
    }
    if (strlen($content) > 10000) {
        return 'Content cannot exceed 10,000 characters';
    }
    return null;
}

function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}
