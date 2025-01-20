<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header('Location: signup.php');
    exit();
}

require '../config/Database.php';
require '../classes/Article.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idArticle = intval($_POST['id_article']);

    if (empty($idArticle)) {
        echo "ID de l'article manquant.";
        exit();
    }

    $database = new Database();
    $conn = $database->getConnection();
    $article = new Article($conn);

    if ($article->approveArticle($idArticle)) {
        header('Location: dashboard_admin.php');
        exit();
    } else {
        echo "Erreur lors de l'approbation de l'article.";
    }
}
?>
