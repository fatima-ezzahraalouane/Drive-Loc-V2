<?php
class Article
{
    private $conn;
    private $table = 'articles';

    public $id_article;
    public $titre;
    public $contenu;
    public $date_creation;
    public $statut;
    public $image_url;
    public $video_url;
    public $id_user;
    public $id_theme;
    public $id_tag;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // public function createArticle()
    // {
    //     try {
    //         $query = "INSERT INTO articles (titre, contenu, statut, image_url, video_url, id_user, id_theme) 
    //                   VALUES (:titre, :contenu, :statut, :image_url, :video_url, :id_user, :id_theme, :id_tag)";
    //         $stmt = $this->conn->prepare($query);
    //         $stmt->bindParam(':titre', $this->titre);
    //         $stmt->bindParam(':contenu', $this->contenu);
    //         $stmt->bindParam(':statut', $this->statut);
    //         $stmt->bindParam(':image_url', $this->image_url);
    //         $stmt->bindParam(':video_url', $this->video_url);
    //         $stmt->bindParam(':id_user', $this->id_user);
    //         $stmt->bindParam(':id_theme', $this->id_theme);
    //         $stmt->bindParam(':id_tag', $this->id_tag);
    //         return $stmt->execute();

    //     } catch (PDOException $e) {
    //         error_log("Erreur lors de la création de l'article : " . $e->getMessage());
    //         return false;
    //     }
    // }

    public function createArticleWithTags(array $tags)
    {
        try {
            // Start a transaction
            $this->conn->beginTransaction();

            // Insert into articles table
            $query = "INSERT INTO articles (titre, contenu, image_url, id_user, id_theme)
                      VALUES (:titre, :contenu, :image_url, :id_user, :id_theme)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':titre', $this->titre);
            $stmt->bindParam(':contenu', $this->contenu);
            // $stmt->bindParam(':statut', $this->statut);
            $stmt->bindParam(':image_url', $this->image_url);
            $stmt->bindParam(':id_user', $_SESSION['user_id']);
            $stmt->bindParam(':id_theme', $this->id_theme);

            if (!$stmt->execute()) {
                throw new Exception("Erreur lors de l'insertion de l'article.");
            }


            // Récupère l'ID de l'article inséré
            $articleId = $this->conn->lastInsertId();


            // Insère les tags liés à cet article
            $sqlTags = "INSERT INTO articles_tags (id_article, id_tag) VALUES (:id_article, :id_tag)";
            $stmtTags = $this->conn->prepare($sqlTags);


            foreach ($tags as $tagId) {
                $stmtTags->bindParam(':id_article', $articleId);
                $stmtTags->bindParam(':id_tag', $tagId);
                if (!$stmtTags->execute()) {
                    throw new Exception("Erreur lors de l'insertion des tags.");
                }
            }

            // Confirme la transaction
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // Annule la transaction en cas d'erreur
            $this->conn->rollBack();
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }


    public function getArticlesByTheme($themeId)
    {
        // Récupérer les articles du thème
        $queryArticles = "
            SELECT * 
            FROM {$this->table} 
            WHERE id_theme = :id_theme
        ";
        $stmt = $this->conn->prepare($queryArticles);
        $stmt->bindParam(':id_theme', $themeId, PDO::PARAM_INT);
        $stmt->execute();
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ajouter les informations supplémentaires pour chaque article
        foreach ($articles as &$article) {
            // Récupérer le nom du thème
            $queryTheme = "
                SELECT nom 
                FROM themes 
                WHERE id_theme = :id_theme
            ";
            $stmtTheme = $this->conn->prepare($queryTheme);
            $stmtTheme->bindParam(':id_theme', $article['id_theme'], PDO::PARAM_INT);
            $stmtTheme->execute();
            $article['theme_nom'] = $stmtTheme->fetchColumn();

            // Récupérer le nom de l'utilisateur
            $queryUser = "
                SELECT username 
                FROM usersite 
                WHERE id_user = :id_user
            ";
            $stmtUser = $this->conn->prepare($queryUser);
            $stmtUser->bindParam(':id_user', $article['id_user'], PDO::PARAM_INT);
            $stmtUser->execute();
            $article['user_name'] = $stmtUser->fetchColumn();

            // Compter le nombre de commentaires
            $queryComments = "
                SELECT COUNT(*) 
                FROM commentaires 
                WHERE id_article = :id_article
            ";
            $stmtComments = $this->conn->prepare($queryComments);
            $stmtComments->bindParam(':id_article', $article['id_article'], PDO::PARAM_INT);
            $stmtComments->execute();
            $article['comment_count'] = $stmtComments->fetchColumn();
        }

        return $articles;
    }



    public function getAllArticlesWithComments()
    {
        // Récupérer tous les articles
        $queryArticles = "
            SELECT * 
            FROM {$this->table}
        ";
        $stmt = $this->conn->prepare($queryArticles);
        $stmt->execute();
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ajouter les informations supplémentaires pour chaque article
        foreach ($articles as &$article) {
            // Récupérer le nom du thème
            $queryTheme = "
                SELECT nom 
                FROM themes 
                WHERE id_theme = :id_theme
            ";
            $stmtTheme = $this->conn->prepare($queryTheme);
            $stmtTheme->bindParam(':id_theme', $article['id_theme'], PDO::PARAM_INT);
            $stmtTheme->execute();
            $article['theme_nom'] = $stmtTheme->fetchColumn();

            // Récupérer le nom de l'utilisateur
            $queryUser = "
                SELECT username 
                FROM usersite 
                WHERE id_user = :id_user
            ";
            $stmtUser = $this->conn->prepare($queryUser);
            $stmtUser->bindParam(':id_user', $article['id_user'], PDO::PARAM_INT);
            $stmtUser->execute();
            $article['user_name'] = $stmtUser->fetchColumn();

            // Compter le nombre de commentaires
            $queryComments = "
                SELECT COUNT(*) 
                FROM commentaires 
                WHERE id_article = :id_article
            ";
            $stmtComments = $this->conn->prepare($queryComments);
            $stmtComments->bindParam(':id_article', $article['id_article'], PDO::PARAM_INT);
            $stmtComments->execute();
            $article['comment_count'] = $stmtComments->fetchColumn();
        }

        return $articles;
    }

    public function getCommentCountByArticleId($id_article)
    {
        $query = "SELECT COUNT(*) AS comment_count FROM commentaires WHERE id_article = :id_article";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_article', $id_article, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['comment_count'];
    }
    

    public function updateArticle()
    {
        $query = "UPDATE articles 
                  SET titre = :titre, contenu = :contenu, statut = :statut, 
                      image_url = :image_url, video_url = :video_url, id_theme = :id_theme 
                  WHERE id_article = :id_article";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_article', $this->id_article);
        $stmt->bindParam(':titre', $this->titre);
        $stmt->bindParam(':contenu', $this->contenu);
        $stmt->bindParam(':statut', $this->statut);
        $stmt->bindParam(':image_url', $this->image_url);
        $stmt->bindParam(':video_url', $this->video_url);
        $stmt->bindParam(':id_theme', $this->id_theme);
        return $stmt->execute();
    }

    public function deleteArticle($id)
    {
        $query = "DELETE FROM articles WHERE id_article = :id_article";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_article', $id);
        return $stmt->execute();
    }

    public function searchArticles($title)
    {
        // Rechercher les articles
        $queryArticles = "
            SELECT * 
            FROM {$this->table} 
            WHERE titre LIKE :title
        ";
        $stmt = $this->conn->prepare($queryArticles);
        $searchTerm = "%" . $title . "%";
        $stmt->bindParam(':title', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ajouter les informations des thèmes et utilisateurs, et le nombre de commentaires
        foreach ($articles as &$article) {
            // Rechercher le nom du thème
            $queryTheme = "
                SELECT nom 
                FROM themes 
                WHERE id_theme = :id_theme
            ";
            $stmtTheme = $this->conn->prepare($queryTheme);
            $stmtTheme->bindParam(':id_theme', $article['id_theme'], PDO::PARAM_INT);
            $stmtTheme->execute();
            $article['theme_nom'] = $stmtTheme->fetchColumn();

            // Rechercher le nom de l'utilisateur
            $queryUser = "
                SELECT username 
                FROM usersite 
                WHERE id_user = :id_user
            ";
            $stmtUser = $this->conn->prepare($queryUser);
            $stmtUser->bindParam(':id_user', $article['id_user'], PDO::PARAM_INT);
            $stmtUser->execute();
            $article['user_name'] = $stmtUser->fetchColumn();

            // Compter le nombre de commentaires
            $queryComments = "
                SELECT COUNT(*) 
                FROM commentaires 
                WHERE id_article = :id_article
            ";
            $stmtComments = $this->conn->prepare($queryComments);
            $stmtComments->bindParam(':id_article', $article['id_article'], PDO::PARAM_INT);
            $stmtComments->execute();
            $article['comment_count'] = $stmtComments->fetchColumn();
        }

        return $articles;
    }



    public function filterArticlesByTags($tagId, $themeId)
    {
        // Récupérer les articles filtrés par tag et thème
        $queryArticles = "
            SELECT a.* 
            FROM articles a
            WHERE a.id_theme = :themeId 
            AND a.id_article IN (
                SELECT at.id_article 
                FROM articles_tags at 
                WHERE at.id_tag = :tagId
            )
        ";
        $stmt = $this->conn->prepare($queryArticles);
        $stmt->bindParam(':tagId', $tagId, PDO::PARAM_INT);
        $stmt->bindParam(':themeId', $themeId, PDO::PARAM_INT);
        $stmt->execute();
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ajouter les informations supplémentaires pour chaque article
        foreach ($articles as &$article) {
            // Récupérer le nom du thème
            $queryTheme = "
                SELECT nom 
                FROM themes 
                WHERE id_theme = :id_theme
            ";
            $stmtTheme = $this->conn->prepare($queryTheme);
            $stmtTheme->bindParam(':id_theme', $article['id_theme'], PDO::PARAM_INT);
            $stmtTheme->execute();
            $article['theme_nom'] = $stmtTheme->fetchColumn();

            // Récupérer le nom de l'utilisateur
            $queryUser = "
                SELECT username 
                FROM usersite 
                WHERE id_user = :id_user
            ";
            $stmtUser = $this->conn->prepare($queryUser);
            $stmtUser->bindParam(':id_user', $article['id_user'], PDO::PARAM_INT);
            $stmtUser->execute();
            $article['user_name'] = $stmtUser->fetchColumn();

            // Compter les commentaires
            $queryComments = "
                SELECT COUNT(*) 
                FROM commentaires 
                WHERE id_article = :id_article
            ";
            $stmtComments = $this->conn->prepare($queryComments);
            $stmtComments->bindParam(':id_article', $article['id_article'], PDO::PARAM_INT);
            $stmtComments->execute();
            $article['comment_count'] = $stmtComments->fetchColumn();
        }

        return $articles;
    }

    public function getPaginatedArticlesByTheme($themeId, $limit, $offset)
    {
        // Récupérer les articles paginés pour le thème
        $query = "SELECT * FROM {$this->table} WHERE id_theme = :id_theme LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_theme', $themeId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ajouter les informations supplémentaires pour chaque article
        foreach ($articles as &$article) {
            // Récupérer le nom du thème
            $queryTheme = "
            SELECT nom 
            FROM themes 
            WHERE id_theme = :id_theme
        ";
            $stmtTheme = $this->conn->prepare($queryTheme);
            $stmtTheme->bindParam(':id_theme', $article['id_theme'], PDO::PARAM_INT);
            $stmtTheme->execute();
            $article['theme_nom'] = $stmtTheme->fetchColumn();

            // Récupérer le nom de l'utilisateur
            $queryUser = "
            SELECT username 
            FROM usersite 
            WHERE id_user = :id_user
        ";
            $stmtUser = $this->conn->prepare($queryUser);
            $stmtUser->bindParam(':id_user', $article['id_user'], PDO::PARAM_INT);
            $stmtUser->execute();
            $article['user_name'] = $stmtUser->fetchColumn();

            // Compter le nombre de commentaires
            $queryComments = "
            SELECT COUNT(*) 
            FROM commentaires 
            WHERE id_article = :id_article
        ";
            $stmtComments = $this->conn->prepare($queryComments);
            $stmtComments->bindParam(':id_article', $article['id_article'], PDO::PARAM_INT);
            $stmtComments->execute();
            $article['comment_count'] = $stmtComments->fetchColumn();
        }

        return $articles;
    }

    public function countArticlesByTheme($themeId)
    {
        $query = "SELECT COUNT(*) FROM {$this->table} WHERE id_theme = :id_theme";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_theme', $themeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    

    public function getArticleById($id)
    {
        $query = "
            SELECT a.*, t.nom AS theme_nom, u.username AS user_name
            FROM articles a
            LEFT JOIN themes t ON a.id_theme = t.id_theme
            LEFT JOIN usersite u ON a.id_user = u.id_user
            WHERE a.id_article = :id
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($article) {
            // Récupérer les tags associés
            $queryTags = "
                SELECT t.nom 
                FROM tags t
                INNER JOIN articles_tags at ON t.id_tag = at.id_tag
                WHERE at.id_article = :id
            ";
            $stmtTags = $this->conn->prepare($queryTags);
            $stmtTags->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtTags->execute();
            $article['tags'] = $stmtTags->fetchAll(PDO::FETCH_ASSOC);
    
            // Récupérer les commentaires associés avec l'auteur et la date
            $queryComments = "
                SELECT c.contenu, c.date_creation, u.username AS auteur
                FROM commentaires c
                LEFT JOIN usersite u ON c.id_user = u.id_user
                WHERE c.id_article = :id
                ORDER BY c.date_creation DESC
            ";
            $stmtComments = $this->conn->prepare($queryComments);
            $stmtComments->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtComments->execute();
            $article['comments'] = $stmtComments->fetchAll(PDO::FETCH_ASSOC);
        }
    
        return $article;
    }
    

}
