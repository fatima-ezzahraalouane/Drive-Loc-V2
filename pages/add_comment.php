<?php
session_start();
require '../config/Database.php';
require '../classes/Commentaire.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['article_id'], $_POST['comment_content'], $_SESSION['user_id'])) {
        $database = new Database();
        $conn = $database->getConnection();
        $commentaire = new Commentaire($conn);

        $articleId = intval($_POST['article_id']);
        $userId = intval($_SESSION['user_id']);
        $content = htmlspecialchars(trim($_POST['comment_content']));

        if ($commentaire->addComment($articleId, $userId, $content)) {
            echo json_encode(['success' => true]);
            exit();
        } else {
            echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'ajout du commentaire']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Données manquantes']);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
    exit();
}
