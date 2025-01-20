<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header('Location: signup.php');
    exit();
}

require '../config/Database.php';
require '../classes/Theme.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $conn = $database->getConnection();

    $theme = new Theme($conn);
    $theme->id_theme = $_POST['id_theme'];
    $theme->nom = $_POST['nom'];
    $theme->description = $_POST['description'];
    $theme->imgUrl = $_POST['imgUrl'];

    if ($theme->updateTheme()) {
        header('Location: dashboard.php?success=1');
    } else {
        header('Location: dashboard.php?error=1');
    }
}

?>