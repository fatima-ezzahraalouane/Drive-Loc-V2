<?php
class Favori
{
    private $conn;
    private $table = 'favoris';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Ajouter un article aux favoris
    public function addFavorite($userId, $articleId)
    {
        $query = "
            INSERT INTO {$this->table} (id_user, id_article) 
            VALUES (:userId, :articleId)
            ON DUPLICATE KEY UPDATE date_ajout = CURRENT_TIMESTAMP
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':articleId', $articleId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Vérifier si un article est dans les favoris
    public function isFavorite($userId, $articleId)
    {
        $query = "
            SELECT COUNT(*) 
            FROM {$this->table} 
            WHERE id_user = :userId AND id_article = :articleId
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':articleId', $articleId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Supprimer un favori
    public function removeFavorite($userId, $articleId)
    {
        $query = "
            DELETE FROM {$this->table} 
            WHERE id_user = :userId AND id_article = :articleId
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':articleId', $articleId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
