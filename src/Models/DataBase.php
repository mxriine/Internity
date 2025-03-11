<?php
$servername = "localhost";
$username = "Internity";  // Remplace par le nom de ton utilisateur MySQL
$password = "in/ternityxx25";  // Remplace par le mot de passe de ton utilisateur MySQL
$dbname = "Internity";  // Remplace par le nom de ta base de données

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}
echo "Connexion réussie !";

// Fermer la connexion
$conn->close();
?>
