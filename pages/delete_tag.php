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
    $id = $_POST['id_tag'] ?? null;

    if (empty($id)) {
        header('Location: dashboard.php?error=empty_id');
        exit();
    }

    if ($tag->deleteTag($id)) {
        header('Location: dashboard.php?success=tag_deleted');
    } else {
        header('Location: dashboard.php?error=delete_failed');
    }
}
?>
