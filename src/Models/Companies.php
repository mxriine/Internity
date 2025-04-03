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
    public function getAllCompanies() {
        $stmt = $this->pdo->query("SELECT * FROM Companies");
        return $stmt->fetchAll();
    }

    public function getTotalCompaniesCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM Companies");
        return $stmt->fetchColumn();
    }


    public function getPaginatedCompanies($limit, $offset, $search, $location)
    {
        try {
            $sql = "SELECT DISTINCT c.*, ci.city_name, ci.city_code, r.region_name
                    FROM Companies c
                    JOIN Located l ON c.company_id = l.company_id
                    JOIN Cities ci ON l.city_id = ci.city_id
                    JOIN Regions r ON ci.region_id = r.region_id";

            $whereClauses = [];
            $params = [];

            // Search by company name
            if (!empty($search)) {
                $whereClauses[] = "c.company_name LIKE :search";
                $params[':search'] = '%' . $search . '%';
            }

            // Filter by location (city name, city code, region name, company address)
            if (!empty($location)) {
                $whereClauses[] = "(ci.city_name LIKE :location OR ci.city_code LIKE :location OR r.region_name LIKE :location OR c.company_address LIKE :location)";
                $params[':location'] = '%' . $location . '%';
            }

            if (!empty($whereClauses)) {
                $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
            }

            $sql .= " LIMIT :limit OFFSET :offset";
            $stmt = $this->pdo->prepare($sql);

            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des entreprises paginées : " . $e->getMessage());
        }
    }

    public function getTotalPaginatedCompaniesCount($search, $location)
    {
        try {
            $sql = "SELECT COUNT(DISTINCT c.company_id)
                    FROM Companies c
                    JOIN Located l ON c.company_id = l.company_id
                    JOIN Cities ci ON l.city_id = ci.city_id
                    JOIN Regions r ON ci.region_id = r.region_id";

            $whereClauses = [];
            $params = [];

            // Handle search
            $keywords = array_filter(array_map('trim', explode(' ', $search)));
            if (!empty($keywords)) {
                if (count($keywords) === 1) {
                    $whereClauses[] = "c.company_name LIKE :word";
                    $params[':word'] = '%' . $keywords[0] . '%';
                } else {
                    $companyName = array_shift($keywords);
                    $whereClauses[] = "c.company_name LIKE :companyName";
                    $params[':companyName'] = '%' . $companyName . '%';
                    $otherParts = [];
                    foreach ($keywords as $index => $word) {
                        $key = ':otherName' . $index;
                        $otherParts[] = "c.company_name LIKE $key";
                        $params[$key] = '%' . $word . '%';
                    }
                    if (!empty($otherParts)) {
                        $whereClauses[] = '(' . implode(' OR ', $otherParts) . ')';
                    }
                }
            }

            // Handle location (city name, city code, region name, company address)
            if (!empty($location)) {
                $whereClauses[] = "(ci.city_name LIKE :location OR ci.city_code LIKE :location OR r.region_name LIKE :location OR c.company_address LIKE :location)";
                $params[':location'] = '%' . $location . '%';
            }

            if (!empty($whereClauses)) {
                $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
            }

            $stmt = $this->pdo->prepare($sql);
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors du comptage des entreprises : " . $e->getMessage());
        }
    }

    // Voir les offres d'une entreprise selon son ID
    public function getCompanyOffers($companyId) {
        $stmt = $this->pdo->prepare("SELECT * FROM Offers WHERE company_id = ?");
        $stmt->bindParam(1, $companyId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Voir une entreprise selon son ID
    public function getCompanyById($companyId) {
        $stmt = $this->pdo->prepare("SELECT 
                c.*,
                ci.*,
                r.*
        FROM Companies c
        JOIN Located l ON c.company_id = l.company_id
        JOIN Cities ci ON l.city_id = ci.city_id
        JOIN Regions r ON ci.region_id = r.region_id
        WHERE c.company_id = :companyId
        ");
            $stmt->bindParam(':companyId', $companyId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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