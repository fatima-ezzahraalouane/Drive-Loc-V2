<?php
class Theme
{
    private $conn;
    private $table = 'themes';

    public $id_theme;
    public $nom;
    public $description;
    public $imgUrl;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function addTheme()
    {
        try {
            $query = "INSERT INTO themes (nom, description, imgUrl) 
                      VALUES (:nom, :description, :imgUrl)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nom', $this->nom);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':imgUrl', $this->imgUrl);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la création du thème : " . $e->getMessage());
            return false;
        }
    }

    public function getAllThemes()
    {
        $query = "SELECT * FROM themes";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getThemeById($id)
    {
        $query = "SELECT * FROM themes WHERE id_theme = :id_theme";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_theme', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateTheme()
    {
        $query = "UPDATE themes 
                  SET nom = :nom, description = :description, imgUrl = :imgUrl 
                  WHERE id_theme = :id_theme";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_theme', $this->id_theme);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':imgUrl', $this->imgUrl);
        return $stmt->execute();
    }

    public function deleteTheme($id)
    {
        $query = "DELETE FROM themes WHERE id_theme = :id_theme";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_theme', $id);
        return $stmt->execute();
    }
}