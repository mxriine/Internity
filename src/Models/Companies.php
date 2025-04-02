<?php

namespace Models;

use PDO;
use Exception;
use PDOException;

class Companies {
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Vérifier les identifiants
    public function checkLogin($email, $password) {
        $stmt = $this->pdo->prepare("SELECT user_password FROM Users WHERE user_email = ?");
        $stmt->bindParam(1, $email);
        $stmt->execute();

        $stored_hashed_password = $stmt->fetchColumn();

        if ($stored_hashed_password) {
            $input_hashed = hash("sha512", $password);

            if ($input_hashed === $stored_hashed_password) {
                return true;
            } else {
                return "Mot de passe incorrect.";
            }
        } else {
            return "Aucun utilisateur trouvé avec cet email.";
        }
    }

    // Voir toutes les entreprises
    public function getAllCompanies() {
        $stmt = $this->pdo->query("SELECT * FROM Companies");
        return $stmt->fetchAll();
    }


    public function getTotalCompaniesCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM Companies");
        return $stmt->fetchColumn();
    }


    public function getPaginatedCompanies($limit, $offset)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM Companies LIMIT :limit OFFSET :offset");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des entreprises paginées : " . $e->getMessage());
        }
    }

    // Voir une entreprise selon son ID
    public function getCompanyById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM Companies WHERE company_id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Créer une entreprise
    public function createCompany($name, $address, $email) {
        $stmt = $this->pdo->prepare("INSERT INTO Companies (company_name, company_address, company_email) VALUES (?, ?, ?)");
        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $address);
        $stmt->bindParam(3, $email);
        return $stmt->execute();
    }

    // Modifier une entreprise
    public function updateCompany($id, $name, $address, $email) {
        $stmt = $this->pdo->prepare("UPDATE Companies SET company_name = ?, company_address = ?, company_email = ? WHERE company_id = ?");
        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $address);
        $stmt->bindParam(3, $email);
        $stmt->bindParam(4, $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Supprimer une entreprise
    public function deleteCompany($id) {
        $stmt = $this->pdo->prepare("DELETE FROM Companies WHERE company_id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Méthode pour tester les fonctionnalités
    public function testMethods() {
        try {
            // Test de la méthode createCompany
            $createResult = $this->createCompany("Nouvelle Entreprise", "123 Rue Exemple", "contact@exemple.com");
            echo "Résultat de la création d'entreprise : " . ($createResult ? "Succès" : "Échec") . "\n";

            if ($createResult) {
                // Récupérer l'ID de la dernière entreprise créée
                $lastInsertId = $this->pdo->lastInsertId();

                // Test de la méthode updateCompany sur cette entreprise
                $updateResult = $this->updateCompany($lastInsertId, "Entreprise Modifiée", "456 Rue Modifiée", "modifie@exemple.com");
                echo "Résultat de la mise à jour d'entreprise : " . ($updateResult ? "Succès" : "Échec") . "\n";

                // Test de la méthode deleteCompany sur cette entreprise
                $deleteResult = $this->deleteCompany($lastInsertId);
                echo "Résultat de la suppression d'entreprise : " . ($deleteResult ? "Succès" : "Échec") . "\n";
            }

        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
}

// require_once __DIR__ ."../Core/Config.php";

// if (php_sapi_name() === 'cli') { // Vérifie si le script est exécuté en ligne de commande
//     try {
//         $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS);
//         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//         $companies = new Companies($pdo); // Instancie la classe avec $pdo
//         $companies->testMethods();        // Appelle la méthode testMethods
//     } catch (PDOException $e) {
//         echo "Erreur de connexion à la base de données : " . $e->getMessage();
//     }
// }