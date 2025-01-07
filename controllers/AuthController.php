<?php
session_start();
include '../config/Database.php';
include '../classes/Usersite.php';

$database = new Database();
$db = $database->getConnection();
$user = new UserSite($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $authenticatedUser = $user->loginUser($email, $password);

    if ($authenticatedUser) {
        $_SESSION['user_id'] = $authenticatedUser['id_user'];
        $_SESSION['username'] = $authenticatedUser['username'];
        $_SESSION['role'] = $authenticatedUser['id_role'];

        if ($authenticatedUser['id_role'] == 1) {
            // Rediriger vers le tableau de bord Admin
            header('Location: ../pages/dashboard.php');
        } else {
            // Rediriger vers l'interface utilisateur
            header('Location: ../pages/accueil.php');
        }
        exit();
        // si des informations incorrects
    } else {
        echo "Identifiants incorrects. Veuillez réessayer.";
    }
}
?>