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
// 🔹 Demande un email et un mot de passe dans le terminal
echo "Entrez votre email : ";
$user_email = trim(fgets(STDIN));

echo "Entrez votre mot de passe : ";
$input_password = trim(fgets(STDIN));

// 🔹 Récupère le mot de passe haché depuis MySQL
$stmt = $conn->prepare("SELECT user_password FROM Users WHERE user_email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$stmt->bind_result($stored_hashed_password);
$stmt->fetch();
$stmt->close();

// 🔹 Vérification du mot de passe
if ($stored_hashed_password) {
    // Hacher l'entrée utilisateur pour comparer
    $input_hashed = hash("sha256", $input_password);

    if ($input_hashed === $stored_hashed_password) {
        echo "✅ Mot de passe correct !\n";
    } else {
        echo "❌ Mot de passe incorrect !\n";
    }
} else {
    echo "❌ Aucun utilisateur trouvé avec cet email !\n";
}

// Fermer la connexion
$conn->close();
?>
