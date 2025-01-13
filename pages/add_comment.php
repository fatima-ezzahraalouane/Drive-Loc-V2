<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header('Location: signup.php');
    exit();
}
require '../config/Database.php';
require '../classes/Commentaire.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $articleId = intval($_POST['id_article']);
    $userId = $_SESSION['user_id'];
    $content = trim($_POST['comment']);

    if (!empty($content)) {
        $database = new Database();
        $conn = $database->getConnection();

        $commentaire = new Commentaire($conn);

        try {
            $commentaire->addComment($articleId, $userId, $content);
            header('Location: details_article.php?id=' . $articleId);
            exit();
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    } else {
        echo 'Le contenu du commentaire ne peut pas être vide.';
    }
}
