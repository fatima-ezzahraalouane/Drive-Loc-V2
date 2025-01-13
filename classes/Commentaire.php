<?php
class Commentaire
{
    private $conn;
    private $table = 'commentaires';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Récupérer les commentaires par article
    public function getCommentsByArticle($articleId)
    {
        $query = "
            SELECT c.id_commentaire, c.contenu, c.date_creation, u.username 
            FROM {$this->table} c
            INNER JOIN usersite u ON c.id_user = u.id_user
            WHERE c.id_article = :articleId
            ORDER BY c.date_creation DESC
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':articleId', $articleId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter un commentaire
    public function addComment($articleId, $userId, $content)
    {
        $query = "INSERT INTO {$this->table} (contenu, id_article, id_user) 
                  VALUES (:content, :articleId, :userId)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':articleId', $articleId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Modifier un commentaire
    public function updateComment($commentId, $userId, $content)
    {
        $query = "
            UPDATE {$this->table} 
            SET contenu = :content 
            WHERE id_commentaire = :commentId AND id_user = :userId
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':commentId', $commentId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Supprimer un commentaire
    public function deleteComment($commentId, $userId)
    {
        $query = "
            DELETE FROM {$this->table} 
            WHERE id_commentaire = :commentId AND id_user = :userId
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':commentId', $commentId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
