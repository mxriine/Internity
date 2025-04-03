<?php

namespace Models;

use PDO;
use PDOException;

class User {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Obtenir le nombre total d'utilisateurs
    public function getTotalUsers() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM Users");
        return $stmt->fetchColumn();
    }

    // Obtenir le nombre d'utilisateurs en fonction d'un rôle
    public function getUsersByRole($role) {
        switch($role) {
            case 'student':
                $stmt = $this->pdo->query("SELECT COUNT(*) FROM Students");
                break;
            case 'pilot':
                $stmt = $this->pdo->query("SELECT COUNT(*) FROM Pilotes");
                break;
            case 'admin':
                $stmt = $this->pdo->query("SELECT COUNT(*) FROM Admins");
                break;
            default:
                throw new \Exception("Rôle non reconnu. Utilisez 'student', 'pilot' ou 'admin'.");
        }
        return $stmt->fetchColumn();
    }

    // Créer un nouvel utilisateur
    public function createUser($surname, $email, $name, $password, $coverletter = null, $cv = null) {
        $hashed_password = hash("sha512", $password);
        $stmt = $this->pdo->prepare("INSERT INTO Users (user_surname, user_email, user_name, user_password, user_coverletter, user_cv) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$surname, $email, $name, $hashed_password, $coverletter, $cv]);
    }

    // Modifier un utilisateur existant
    public function updateUser($user_id, $surname = null, $email = null, $name = null, $password = null, $coverletter = null, $cv = null) {
        $fields = [];
        $params = [];

        if (!is_null($surname)) {
            $fields[] = "user_surname = ?";
            $params[] = $surname;
        }
        if (!is_null($email)) {
            $fields[] = "user_email = ?";
            $params[] = $email;
        }
        if (!is_null($name)) {
            $fields[] = "user_name = ?";
            $params[] = $name;
        }
        if (!is_null($password)) {
            $fields[] = "user_password = ?";
            $params[] = hash("sha512", $password);
        }
        if (!is_null($coverletter)) {
            $fields[] = "user_coverletter = ?";
            $params[] = $coverletter;
        }
        if (!is_null($cv)) {
            $fields[] = "user_cv = ?";
            $params[] = $cv;
        }

        if (empty($fields)) {
            throw new \Exception("Aucun champ à mettre à jour.");
        }

        $params[] = $user_id;
        $sql = "UPDATE Users SET " . implode(", ", $fields) . " WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // Supprimer un utilisateur
    public function deleteUser($user_id) {
        $stmt = $this->pdo->prepare("DELETE FROM Users WHERE user_id = ?");
        return $stmt->execute([$user_id]);
    }
}

// =====================
// CODE DE TESTS
// =====================

// echo "=== Début des tests ===\n";

// // Création d'une instance PDO pour l'injection de dépendance
// try {
//     $pdo = new PDO("mysql:host=localhost;dbname=Internity", "admin", "mdp");
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
// } catch(PDOException $e) {
//     die("Erreur de connexion pour les tests : " . $e->getMessage());
// }

// // Création de l'instance du modèle User avec l'objet PDO injecté
// $userModel = new User($pdo);

// // Enregistrer le nombre initial d'utilisateurs
// $initialTotal = $userModel->getTotalUsers();
// echo "Nombre total d'utilisateurs initial : $initialTotal\n";

// // --- Test de création d'utilisateur ---
// $emailTest = "testuser_" . time() . "@example.com";
// $createResult = $userModel->createUser("Test", $emailTest, "UserTest", "testpassword");
// if ($createResult) {
//     echo "Création de l'utilisateur réussie.\n";
// } else {
//     echo "Erreur lors de la création de l'utilisateur.\n";
// }

// // Vérifier que le nombre d'utilisateurs a augmenté d'une unité
// $newTotal = $userModel->getTotalUsers();
// echo "Nombre total d'utilisateurs après création : $newTotal\n";
// if ($newTotal != $initialTotal + 1) {
//     echo "Erreur : Le nombre d'utilisateurs n'a pas augmenté correctement.\n";
// } else {
//     echo "Le nombre d'utilisateurs a augmenté correctement.\n";
// }

// // Récupérer l'ID de l'utilisateur créé grâce à son email
// $stmt = $pdo->prepare("SELECT user_id, user_surname, user_email FROM Users WHERE user_email = ?");
// $stmt->execute([$emailTest]);
// $userData = $stmt->fetch();
// if ($userData) {
//     $userId = $userData['user_id'];
//     echo "Utilisateur créé avec user_id : $userId\n";
// } else {
//     die("Erreur : L'utilisateur créé n'a pas été trouvé dans la base de données.\n");
// }

// // --- Test de modification d'utilisateur ---
// // Mise à jour du nom et de l'email de l'utilisateur créé
// $newSurname = "UpdatedTest";
// $newEmail = "updated_" . $emailTest;
// $updateResult = $userModel->updateUser($userId, $newSurname, $newEmail);
// if ($updateResult) {
//     echo "Mise à jour de l'utilisateur réussie.\n";
// } else {
//     echo "Erreur lors de la mise à jour de l'utilisateur.\n";
// }

// // Vérifier la mise à jour directement dans la base
// $stmt = $pdo->prepare("SELECT user_surname, user_email FROM Users WHERE user_id = ?");
// $stmt->execute([$userId]);
// $updatedUserData = $stmt->fetch();
// if ($updatedUserData && $updatedUserData['user_surname'] === $newSurname && $updatedUserData['user_email'] === $newEmail) {
//     echo "Vérification de la mise à jour réussie : nom et email modifiés.\n";
// } else {
//     echo "Erreur : La mise à jour n'a pas été correctement effectuée.\n";
// }

// // --- Test de la fonction getUsersByRole ---
// // On affiche le nombre d'utilisateurs pour chaque rôle (ces valeurs dépendent des données existantes)
// try {
//     $studentsCount = $userModel->getUsersByRole('student');
//     $pilotsCount   = $userModel->getUsersByRole('pilot');
//     $adminsCount   = $userModel->getUsersByRole('admin');
//     echo "Nombre d'étudiants : $studentsCount\n";
//     echo "Nombre de pilotes  : $pilotsCount\n";
//     echo "Nombre d'admins   : $adminsCount\n";
// } catch (\Exception $e) {
//     echo "Erreur dans getUsersByRole : " . $e->getMessage() . "\n";
// }

// // --- Test de suppression d'utilisateur ---
// // Suppression de l'utilisateur créé
// $deleteResult = $userModel->deleteUser($userId);
// if ($deleteResult) {
//     echo "Suppression de l'utilisateur réussie.\n";
// } else {
//     echo "Erreur lors de la suppression de l'utilisateur.\n";
// }

// // Vérifier que le nombre total d'utilisateurs est revenu à la valeur initiale
// $finalTotal = $userModel->getTotalUsers();
// echo "Nombre total d'utilisateurs après suppression : $finalTotal\n";
// if ($finalTotal != $initialTotal) {
//     echo "Erreur : Le nombre d'utilisateurs n'est pas revenu à la valeur initiale.\n";
// } else {
//     echo "Le nombre d'utilisateurs est revenu à la valeur initiale.\n";
// }

// echo "=== Fin des tests ===\n";