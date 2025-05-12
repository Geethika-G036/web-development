<?php
function redirect($location)
{
    header("Location: $location");
    exit;
}

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function display_message($message, $type = 'success')
{
    return "<div class='alert alert-$type alert-dismissible fade show' role='alert'>
                $message
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
}

function paginate($table, $per_page = 5, $conditions = "", $params = [])
{
    global $pdo;

    // Get total records
    $count_sql = "SELECT COUNT(*) FROM $table";
    if (!empty($conditions)) {
        $count_sql .= " WHERE $conditions";
    }
    $stmt = $pdo->prepare($count_sql);
    $stmt->execute($params);
    $total_records = $stmt->fetchColumn();

    // Calculate total pages
    $total_pages = ceil($total_records / $per_page);

    // Get current page
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $current_page = max(1, min($current_page, $total_pages));

    // Calculate offset
    $offset = ($current_page - 1) * $per_page;

    return [
        'total_records' => $total_records,
        'total_pages' => $total_pages,
        'current_page' => $current_page,
        'per_page' => $per_page,
        'offset' => $offset
    ];
}
