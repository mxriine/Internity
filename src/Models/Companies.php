<?php

namespace Models;

use PDO;
use Exception;
use PDOException;

class Companies
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    //
    // ================================
    // SECTION 1 : Récupération globale
    // ================================

    public function getAllCompanies()
    {
        $stmt = $this->pdo->query("SELECT * FROM Companies");
        return $stmt->fetchAll();
    }

    public function getTotalCompaniesCount()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM Companies");
        return $stmt->fetchColumn();
    }

    //
    // ================================
    // SECTION 2 : Pagination + Recherche
    // ================================

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

            if (!empty($search)) {
                $whereClauses[] = "c.company_name LIKE :search";
                $params[':search'] = '%' . $search . '%';
            }

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

            $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

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

    //
    // ================================
    // SECTION 3 : Détail et relations
    // ================================

    public function getCompanyById($companyId)
    {
        $stmt = $this->pdo->prepare("
            SELECT c.*, ci.*, r.*
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

    public function getCompanyOffers($companyId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Offers WHERE company_id = ?");
        $stmt->bindParam(1, $companyId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //
    // ================================
    // SECTION 4 : CRUD Entreprises
    // ================================
    public function createCompany(array $data): bool
    {
        $this->pdo->beginTransaction();

        try {
            // 1. Vérifier que la ville existe bien
            $stmtCity = $this->pdo->prepare("
            SELECT city_id FROM Cities WHERE city_name = ? AND city_code = ?
        ");
            $stmtCity->execute([$data['city_name'], $data['city_code']]);
            $city = $stmtCity->fetch(PDO::FETCH_ASSOC);

            if (!$city) {
                throw new Exception("La ville spécifiée n'existe pas dans la base de données.");
            }

            $cityId = $city['city_id'];

            // 2. Insérer l'entreprise
            $stmt = $this->pdo->prepare("
            INSERT INTO Companies (
                company_name, company_desc, company_business,
                company_email, company_phone, company_address, company_averagerate
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
            $stmt->execute([
                $data['company_name'],
                $data['company_desc'] ?? null,
                $data['company_business'] ?? null,
                $data['company_email'],
                $data['company_phone'] ?? null,
                $data['company_address'] ?? null,
                0.00
            ]);

            $companyId = $this->pdo->lastInsertId();

            // 3. Lier dans Located
            $stmtLocated = $this->pdo->prepare("
            INSERT INTO Located (company_id, city_id) VALUES (?, ?)
        ");
            $stmtLocated->execute([$companyId, $cityId]);

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception("Erreur lors de la création de l'entreprise : " . $e->getMessage());
        }
    }


    public function updateCompany(int $id, array $data): bool
    {
        $this->pdo->beginTransaction();

        try {
            // 1. Mettre à jour les données de l'entreprise
            $stmt = $this->pdo->prepare("
            UPDATE Companies SET
                company_name = ?, company_desc = ?, company_business = ?,
                company_email = ?, company_phone = ?, company_address = ?
            WHERE company_id = ?
        ");

            $stmt->execute([
                $data['company_name'],
                $data['company_desc'] ?? null,
                $data['company_business'] ?? null,
                $data['company_email'],
                $data['company_phone'] ?? null,
                $data['company_address'] ?? null,
                $id
            ]);

            // 2. Vérifier ou créer la ville
            $stmtCity = $this->pdo->prepare("
            SELECT city_id FROM Cities WHERE city_name = ? AND city_code = ?
        ");
            $stmtCity->execute([$data['city_name'], $data['city_code']]);
            $city = $stmtCity->fetch(PDO::FETCH_ASSOC);

            if ($city) {
                $cityId = $city['city_id'];
            } else {
                $insertCity = $this->pdo->prepare("
                INSERT INTO Cities (city_name, city_code, region_id)
                VALUES (?, ?, ?)
            ");
                $insertCity->execute([
                    $data['city_name'],
                    $data['city_code'],
                    $data['region_id'] ?? 1,
                ]);
                $cityId = $this->pdo->lastInsertId();
            }

            // 3. Mettre à jour Located (delete + insert ou upsert)
            $this->pdo->prepare("DELETE FROM Located WHERE company_id = ?")->execute([$id]);

            $stmtLocated = $this->pdo->prepare("
            INSERT INTO Located (company_id, city_id)
            VALUES (?, ?)
        ");
            $stmtLocated->execute([$id, $cityId]);

            $this->pdo->commit();
            return true;

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new Exception("Erreur lors de la mise à jour de l'entreprise : " . $e->getMessage());
        }
    }


    public function deleteCompany($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM Companies WHERE company_id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    //
    // ================================
    // SECTION 5 : Méthode de test
    // ================================

    /*public function testMethods()
    {
        try {
            $createResult = $this->createCompany("Nouvelle Entreprise", "123 Rue Exemple", "contact@exemple.com");
            echo "Création : " . ($createResult ? "Succès" : "Échec") . "\n";

            if ($createResult) {
                $lastInsertId = $this->pdo->lastInsertId();

                $updateResult = $this->updateCompany($lastInsertId, "Entreprise Modifiée", "456 Rue Modifiée", "modifie@exemple.com");
                echo "Mise à jour : " . ($updateResult ? "Succès" : "Échec") . "\n";

                $deleteResult = $this->deleteCompany($lastInsertId);
                echo "Suppression : " . ($deleteResult ? "Succès" : "Échec") . "\n";
            }

        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }*/
}
