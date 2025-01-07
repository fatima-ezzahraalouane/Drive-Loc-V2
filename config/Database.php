<?php 
// GestionBaseDeDonnées
class Database {
    private $host = '127.0.0.1';
    private $db_name = 'driveloc';
    private $username = 'root';
    private $password = '';
    public $conn;                     // Propriété pour stocker la connexion

    // Méthode pour établir la connexion
    public function getConnection() {
        $this->conn = null; // Initialise la connexion à null
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                password: $this->password
            );
            // Activation du mode d'affichage des erreurs
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
        return $this->conn; // Retourne l'objet connexion PDO
    }

    // Méthode pour fermer la connexion
    public function closeConnection() {
        $this->conn = null; // Ferme la connexion en réinitialisant à null
    }
}
?>
