<?php
require '../config/Database.php';
require '../classes/Commentaire.php';

if (isset($_GET['id_article'])) {
    $database = new Database();
    $conn = $database->getConnection();
    $commentaire = new Commentaire($conn);

    $articleId = intval($_GET['id_article']);
    $comments = $commentaire->getCommentsByArticle($articleId);

    echo json_encode($comments);
    exit();
} else {
    echo json_encode(['error' => 'Invalid request']);
    exit();
}
