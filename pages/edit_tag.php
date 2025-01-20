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
    $newName = trim($_POST['nom_tag'] ?? '');

    if (empty($id) || empty($newName)) {
        header('Location: dashboard.php?error=empty_fields');
        exit();
    }

    if ($tag->updateTag($id, $newName)) {
        header('Location: dashboard.php?success=tag_updated');
    } else {
        header('Location: dashboard.php?error=update_failed');
    }
}
?>
