<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header('Location: signup.php');
    exit();
}

require '../config/Database.php';
require '../classes/Article.php';

// Connexion à la base de données
$database = new Database();
$conn = $database->getConnection();

// Vérifier si l'ID de l'article est passé dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID d'article invalide.";
    exit();
}

$id_article = intval($_GET['id']);

// Récupérer les détails de l'article
$article = new Article($conn);
$articleDetails = $article->getArticleById($id_article);

if (!$articleDetails) {
    echo "Article introuvable.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'article</title>
    <link rel="icon" href="../assets/img/loclogo-removebg-preview.png">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .article-container {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .article-image {
            flex: 1;
            max-width: 300px;
        }

        .article-details {
            flex: 2;
        }

        .comments-section .card {
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .comments-section .card-title {
            font-size: 1.1em;
            font-weight: bold;
        }

        .comments-section .card-text {
            font-size: 1em;
            color: #333;
        }

        .comments-section .text-muted {
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <h1 class="text-primary mb-4"><?= htmlspecialchars($articleDetails['titre']) ?></h1>

        <div class="article-container">
            <!-- Image -->
            <div class="article-image">
                <img src="<?= htmlspecialchars($articleDetails['image_url']) ?>"
                    class="img-fluid rounded"
                    alt="Image de l'article">
            </div>

            <!-- Détails -->
            <div class="article-details">
                <p><strong>Date de publication :</strong> <?= htmlspecialchars($articleDetails['date_creation']) ?></p>
                <p><strong>Thème :</strong> <?= htmlspecialchars($articleDetails['theme_nom']) ?></p>
                <p><strong>Auteur :</strong> <?= htmlspecialchars($articleDetails['user_name']) ?></p>
                <p><?= nl2br(htmlspecialchars($articleDetails['contenu'])) ?></p>

                <h3>Tags associés :</h3>
                <ul>
                    <?php foreach ($articleDetails['tags'] as $tag): ?>
                        <li><?= htmlspecialchars($tag['nom']) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="mt-4">
            <!-- Bouton Ajouter aux favoris -->
            <a href="add_to_favorites.php?id=<?= $id_article ?>" class="btn btn-primary">
                <i class="fas fa-heart"></i> Ajouter aux favoris
            </a>

        </div>

        <!-- Section commentaires -->
        <div class="comments-section mt-4">
            <h3>Commentaires :</h3>

            <?php if (!empty($articleDetails['comments'])): ?>
                <?php foreach ($articleDetails['comments'] as $comment): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-user-circle"></i> Auteur : <?= htmlspecialchars($comment['auteur'] ?? 'Anonyme') ?>
                            </h5>
                            <p class="card-text"><?= nl2br(htmlspecialchars($comment['contenu'])) ?></p>
                            <p class="text-muted mb-0" style="font-size: 0.9em;">
                                <i class="fas fa-calendar-alt"></i> Posté le : <?= htmlspecialchars($comment['date_creation']) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">Aucun commentaire pour le moment. Soyez le premier à commenter !</p>
            <?php endif; ?>

            <!-- Formulaire pour ajouter un commentaire -->
            <form action="add_comment.php" method="POST" class="mt-3">
                <div class="form-group">
                    <label for="comment" class="mb-2">Ajouter un commentaire :</label>
                    <textarea name="comment" id="comment" class="form-control" rows="3" placeholder="Votre commentaire..."></textarea>
                </div>
                <input type="hidden" name="id_article" value="<?= $id_article ?>">
                <button type="submit" class="btn btn-primary mt-2">Ajouter</button>
            </form>

            <!-- Bouton Annuler -->
            <div class="mt-3">
    <a href="affi_articles.php?id_theme=<?= htmlspecialchars($articleDetails['id_theme']) ?>
       <?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>
       <?= isset($_GET['tag']) ? '&tag=' . htmlspecialchars($_GET['tag']) : '' ?>" 
       class="btn btn-secondary">
        Annuler
    </a>
</div>

        </div>


    </div>
</body>

</html>