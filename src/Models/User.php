<?php

namespace Models;

use PDO;
use PDOException;

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = new PDO("mysql:host=localhost;dbname=Internity;charset=utf8", "Internity", "in/ternityxx25");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    // Vérifier les identifiants
    public function checkLogin($email, $password) {
        $stmt = $this->pdo->prepare("SELECT user_password FROM Users WHERE user_email = ?");
        $stmt->bindParam(1, $email);
        $stmt->execute();

        $stored_hashed_password = $stmt->fetchColumn();

        if ($stored_hashed_password) {
            $input_hashed = hash("sha256", $password);

            if ($input_hashed === $stored_hashed_password) {
                return true;
            } else {
                return "Mot de passe incorrect.";
            }
        } else {
            return "Aucun utilisateur trouvé avec cet email.";
        }
    }
}
