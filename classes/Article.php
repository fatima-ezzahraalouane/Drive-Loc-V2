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
        $query = "
            SELECT a.*, 
                   t.nom AS theme_nom, 
                   u.username AS user_name,
                   (SELECT COUNT(*) 
                    FROM commentaires c 
                    WHERE c.id_article = a.id_article) AS comment_count
            FROM {$this->table} a
            LEFT JOIN themes t ON a.id_theme = t.id_theme
            LEFT JOIN usersite u ON a.id_user = u.id_user
            WHERE a.id_theme = :id_theme
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_theme', $themeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    


    public function getAllArticlesWithComments()
    {
        $query = "
            SELECT a.*, 
                   t.nom AS theme_nom, 
                   u.username AS user_name,
                   (SELECT COUNT(*) 
                    FROM commentaires c 
                    WHERE c.id_article = a.id_article) AS comment_count
            FROM {$this->table} a
            LEFT JOIN themes t ON a.id_theme = t.id_theme
            LEFT JOIN usersite u ON a.id_user = u.id_user
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCommentCountByArticleId($id_article)
    {
        $query = "SELECT COUNT(*) AS comment_count FROM commentaires WHERE id_article = :id_article";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_article', $id_article);
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
        $query = "
            SELECT a.*, 
                   t.nom AS theme_nom, 
                   u.username AS user_name,
                   (SELECT COUNT(*) 
                    FROM commentaires c 
                    WHERE c.id_article = a.id_article) AS comment_count
            FROM {$this->table} a
            LEFT JOIN themes t ON a.id_theme = t.id_theme
            LEFT JOIN usersite u ON a.id_user = u.id_user
            WHERE a.titre LIKE :title
        ";
        $stmt = $this->conn->prepare($query);
        $searchTerm = "%" . $title . "%";
        $stmt->bindParam(':title', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function filterArticlesByTags($tagId, $themeId)
    {
        $query = "
            SELECT a.*, 
                   t.nom AS theme_nom, 
                   u.username AS user_name,
                   (SELECT COUNT(*) 
                    FROM commentaires c 
                    WHERE c.id_article = a.id_article) AS comment_count
            FROM articles a
            LEFT JOIN articles_tags at ON a.id_article = at.id_article
            LEFT JOIN tags tg ON at.id_tag = tg.id_tag
            LEFT JOIN themes t ON a.id_theme = t.id_theme
            LEFT JOIN usersite u ON a.id_user = u.id_user
            WHERE at.id_tag = :tagId AND a.id_theme = :themeId
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tagId', $tagId, PDO::PARAM_INT);
        $stmt->bindParam(':themeId', $themeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}
