<?php
session_start();
require '../config/Database.php';
require '../classes/Commentaire.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header('Location: signup.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $commentId = intval($_POST['comment_id']);
    $articleId = intval($_POST['article_id']);
    $content = trim($_POST['comment']);

    // Vérifier si le contenu n'est pas vide
    if (empty($content)) {
        echo "Le commentaire ne peut pas être vide.";
        exit();
    }

    // Connexion à la base de données
    $database = new Database();
    $conn = $database->getConnection();
    $commentaire = new Commentaire($conn);

    // Mise à jour du commentaire
    $isUpdated = $commentaire->updateComment($commentId, $_SESSION['user_id'], $content);

    if ($isUpdated) {
        // Rediriger vers les détails de l'article après mise à jour réussie
        header("Location: details_article.php?id=$articleId");
        exit();
    } else {
        echo "Erreur lors de la mise à jour du commentaire.";
        exit();
    }
}
?>
