<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header('Location: signup.php');
    exit();
}

require '../config/Database.php';
require '../classes/Favori.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID de l'article invalide.";
    exit();
}

$articleId = intval($_GET['id']);
$userId = $_SESSION['user_id'];

// Connexion à la base de données
$database = new Database();
$conn = $database->getConnection();

// Ajouter l'article aux favoris
$favori = new Favori($conn);
if ($favori->addFavorite($userId, $articleId)) {
    header("Location: details_article.php?id=$articleId&success=favori");
    exit();
} else {
    echo "Erreur lors de l'ajout aux favoris.";
}
?>
