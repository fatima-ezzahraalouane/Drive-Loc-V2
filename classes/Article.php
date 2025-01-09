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

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createArticle()
    {
        try {
            $query = "INSERT INTO articles (titre, contenu, statut, image_url, video_url, id_user, id_theme) 
                      VALUES (:titre, :contenu, :statut, :image_url, :video_url, :id_user, :id_theme)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':titre', $this->titre);
            $stmt->bindParam(':contenu', $this->contenu);
            $stmt->bindParam(':statut', $this->statut);
            $stmt->bindParam(':image_url', $this->image_url);
            $stmt->bindParam(':video_url', $this->video_url);
            $stmt->bindParam(':id_user', $this->id_user);
            $stmt->bindParam(':id_theme', $this->id_theme);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de l'article : " . $e->getMessage());
            return false;
        }
    }

    public function getAllArticles()
    {
        $query = "SELECT a.*, t.nom AS theme_nom 
                  FROM articles a 
                  LEFT JOIN themes t ON a.id_theme = t.id_theme";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticleById($id)
    {
        $query = "SELECT * FROM articles WHERE id_article = :id_article";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_article', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
}
?>
