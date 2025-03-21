<?php

/*
ini_set('display_errors', 1);
error_reporting(E_ALL);


// Démarrez la session
session_start();

// Inclure le fichier de connexion à la base de données
require_once(__DIR__ . '/../Core/DataBase.php');


// Initialiser les variables
$error_message = "";

// Vérifier si le formulaire a été soumis
if (isset($_POST['email']) && isset($_POST['password'])) {
    $user_email = $_POST['email'];
    $input_password = $_POST['password'];

    // 🔹 Récupère le mot de passe haché depuis MySQL avec PDO
    $stmt = $conn->prepare("SELECT user_password FROM Users WHERE user_email = :email");
    $stmt->bindParam(':email', $user_email);
    $stmt->execute();

    // 🔹 Vérification du mot de passe
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $stored_hashed_password = $row['user_password'];

        // 🔹 Hacher l'entrée utilisateur avec SHA-256 pour comparaison
        $input_hashed = hash("sha256", $input_password);

        // Comparer les deux hachages
        if ($input_hashed === $stored_hashed_password) {
            // Connexion réussie, rediriger vers la page d'accueil
            header('Location: /index.php');
            exit();
        } else {
            // Mot de passe incorrect
            $error_message = "❌ Mot de passe incorrect !";
        }
    } else {
        // Aucun utilisateur trouvé avec cet email
        $error_message = "❌ Aucun utilisateur trouvé avec cet email !";
    }

    // Si une erreur s'est produite, afficher le message d'erreur dans un cookie
    if ($error_message) {
        setcookie('error_message', $error_message, time() + 10, "/"); // Cookie valide pendant 10 secondes
    }
}

// Fermer la connexion
$stmt = null;

*/
?>
