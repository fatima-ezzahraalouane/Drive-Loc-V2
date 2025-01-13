<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header('Location: signup.php');
    exit();
}

require '../config/Database.php';
require '../classes/Commentaire.php';

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentId = intval($_POST['comment_id']);
    $articleId = intval($_POST['article_id']);

    // Récupérer le commentaire
    $query = "SELECT * FROM commentaires WHERE id_commentaire = :commentId AND id_user = :userId";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':commentId', $commentId, PDO::PARAM_INT);
    $stmt->bindParam(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$comment) {
        echo "Commentaire introuvable ou vous n'avez pas la permission de le modifier.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le commentaire</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container py-5">
        <h1 class="text-primary mb-4">Modifier le commentaire</h1>
        <form action="../pages/process_update_comment.php" method="POST">
            <div class="form-group">
                <label for="comment">Commentaire :</label>
                <textarea name="comment" id="comment" class="form-control" rows="3"><?= htmlspecialchars($comment['contenu']) ?></textarea>
            </div>
            <input type="hidden" name="comment_id" value="<?= $comment['id_commentaire'] ?>">
            <input type="hidden" name="article_id" value="<?= $articleId ?>">
            <button type="submit" class="btn btn-success mt-3">Enregistrer</button>
            <a href="details_article.php?id=<?= $articleId ?>" class="btn btn-secondary mt-3">Annuler</a>
        </form>
    </div>
</body>
</html>