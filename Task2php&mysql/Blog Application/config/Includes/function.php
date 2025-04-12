<?php
function redirect($location) {
    header("Location: $location");
    exit;
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function display_message($message, $type = 'success') {
    return "<div class='alert alert-$type'>$message</div>";
}
?>