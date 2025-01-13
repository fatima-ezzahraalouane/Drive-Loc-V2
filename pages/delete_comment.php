<?php
session_start();
require '../config/Database.php';
require '../classes/Commentaire.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentId = intval($_POST['comment_id']);
    $articleId = intval($_POST['article_id']);

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
        header('Location: signup.php');
        exit();
    }

    $database = new Database();
    $conn = $database->getConnection();
    $commentaire = new Commentaire($conn);

    if ($commentaire->deleteComment($commentId, $_SESSION['user_id'])) {
        header("Location: details_article.php?id=$articleId");
        exit();
    } else {
        echo "Erreur lors de la suppression du commentaire.";
    }
}
?>
