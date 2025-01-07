<?php
require_once '../config/Database.php';

class Categorie
{
    protected $nom;
    protected $description;
    private $conn;

    // Constructeur avec injection de la connexion à la base de données
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Ajouter une catégorie
    public function ajouterCategorie($cat_nom, $cat_desc)
    {
        try {
            $this->nom = $cat_nom;
            $this->description = $cat_desc;

            $query = "INSERT INTO categorie (nom, description) 
                    VALUES (:nom, :description)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':nom', $this->nom);
            $stmt->bindParam(':description', $this->description);

            if ($stmt->execute()) {
                header('Location: ../pages/dashboard.php');
                exit();
            }
        } catch (PDOException $e) {
            die("Erreur lors de l'ajout de la catégorie : " . $e->getMessage());
        }
    }

    // Afficher les catégories
    public function showCategorie()
    {
        try {
            $query = "SELECT * FROM categorie";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des catégories : " . $e->getMessage());
        }

        return [];
    }
}
?>
