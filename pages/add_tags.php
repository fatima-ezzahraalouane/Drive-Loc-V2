<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header('Location: signup.php');
    exit();
}

require '../config/Database.php';
require '../classes/Tags.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $conn = $database->getConnection();

    $tag = new Tag($conn);

    $tags = $_POST['tags'] ?? [];
    $tags = array_filter(array_map('trim', $tags)); // Remove empty and trim spaces

    if (empty($tags)) {
        header('Location: dashboard.php?error=empty_tags');
        exit();
    }

    $success = $tag->createMultipleTags($tags);

    if ($success) {
        header('Location: dashboard.php?success=tags_added');
    } else {
        header('Location: dashboard.php?error=add_failed');
    }
}
?>
