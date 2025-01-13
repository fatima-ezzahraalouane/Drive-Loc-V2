<?php
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
