<?php
// Démarrez la session
session_start();

// Inclure le fichier de connexion à la base de données
require_once('DataBase.php');

// Initialiser les variables
$error_message = "";

// Demander les informations de l'utilisateur
echo "Entrez votre email : ";
$user_email = trim(fgets(STDIN));

echo "Entrez votre mot de passe : ";
$input_password = trim(fgets(STDIN));

// 🔹 Récupère le mot de passe haché depuis MySQL avec PDO
$stmt = $conn->prepare("SELECT user_password FROM Users WHERE user_email = :email");
$stmt->bindParam(':email', $user_email);
$stmt->execute();

// 🔹 Vérification du mot de passe
if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $stored_hashed_password = $row['user_password'];

    // 🔹 Hacher l'entrée utilisateur avec SHA-256 pour comparaison
    $input_hashed = hash("sha512", $input_password);

    // Comparer les deux hachages
    if ($input_hashed === $stored_hashed_password) {
        echo "✅ Mot de passe correct !\n";
    } else {
        echo "❌ Mot de passe incorrect !\n";
    }
} else {
    echo "❌ Aucun utilisateur trouvé avec cet email !\n";
}

// Fermer la connexion
$stmt = null;
