<?php
class Tag
{
    private $conn;
    private $table = 'tags';

    public $id_tag;
    public $nom;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createOrGetTag($name)
    {
        try {
            $query = "SELECT id_tag FROM tags WHERE nom = :name";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            $tag = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($tag) {
                return $tag['id_tag'];
            }

            $query = "INSERT INTO tags (nom) VALUES (:name)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->execute();

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur lors de la création du tag : " . $e->getMessage());
            return null;
        }
    }

    public function createMultipleTags(array $tags)
    {
        $tagIds = [];
        foreach ($tags as $tagName) {
            $tagIds[] = $this->createOrGetTag(trim($tagName));
        }
        return $tagIds;
    }

    public function linkTagToArticle($articleId, $tagId)
    {
        try {
            $query = "INSERT INTO articles_tags (id_article, id_tag) VALUES (:id_article, :id_tag)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_article', $articleId);
            $stmt->bindParam(':id_tag', $tagId);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la liaison du tag à l'article : " . $e->getMessage());
            return false;
        }
    }
    
    public function getAllTags()
    {
        try {
            $query = "SELECT * FROM tags";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des tags : " . $e->getMessage());
            return [];
        }
    }

    public function updateTag($id, $newName)
    {
        try {
            $query = "UPDATE tags SET nom = :newName WHERE id_tag = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':newName', $newName);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour du tag : " . $e->getMessage());
            return false;
        }
    }

    public function deleteTag($id)
    {
        try {
            $query = "DELETE FROM tags WHERE id_tag = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression du tag : " . $e->getMessage());
            return false;
        }
    }
}
