<?php
class UserSite {
    private $conn;
    private $table = 'usersite';

    public $id_user;
    public $username;
    public $email;
    public $password;
    public $id_role; // 1 = Admin, 2 = Utilisateur

    public function __construct($db) {
        $this->conn = $db;
    }

    // Méthode pour l'inscription des utilisateurs
    public function registerUser() {
        $query = "INSERT INTO usersite (username, email, password, id_role) 
                  VALUES (:username, :email, :password, 2)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', password_hash($this->password, PASSWORD_DEFAULT));
        return $stmt->execute();
    }

    // Méthode pour la connexion (Admin et Utilisateur)
    public function loginUser($email, $password) {
        $query = "SELECT * FROM usersite WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user; // Retourne les détails de l'utilisateur si connexion réussie
        }
        return false; // si Connexion échouée
    }
}
?>
