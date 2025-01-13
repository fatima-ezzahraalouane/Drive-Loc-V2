<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header('Location: signup.php');
    exit();
}

require '../config/Database.php';
require '../classes/Article.php';

if (isset($_GET['id'])) {
    $articleId = intval($_GET['id']);
    $database = new Database();
    $conn = $database->getConnection();

    $article = new Article($conn);
    $details = $article->getArticleById($articleId); // Implémente cette méthode dans ta classe Article
    
    echo json_encode($details);
    exit();
}

http_response_code(400);
echo json_encode(['error' => 'Invalid request']);
?>
