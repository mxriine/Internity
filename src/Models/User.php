<?php

namespace Models;

use PDO;
use PDOException;
use Exception;

class User
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // =========================================
    // SECTION 1 : COMPTEURS UTILISATEURS
    // =========================================

    public function getUsers(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM Users");
        return $stmt->fetchColumn();
    }

    public function getUsersByRole(string $role): int
    {
        $tables = [
            'student' => 'Students',
            'pilote' => 'Pilotes',
            'admin' => 'Admins'
        ];

        if (!array_key_exists($role, $tables)) {
            throw new Exception("Rôle non reconnu. Utilisez 'student', 'pilot' ou 'admin'.");
        }

        $stmt = $this->pdo->query("SELECT COUNT(*) FROM " . $tables[$role]);
        return $stmt->fetchColumn();
    }

    public function getTotalStudentsCount(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) AS student_count FROM Students");
        return $stmt->fetch(PDO::FETCH_ASSOC)['student_count'] ?? 0;
    }

    public function getTotalPiloteCount(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) AS pilote_count FROM Pilotes");
        return $stmt->fetch(PDO::FETCH_ASSOC)['pilote_count'] ?? 0;
    }

    // =========================================
    // SECTION 2 : CRUD UTILISATEURS
    // =========================================

    public function createUser(string $surname, string $email, string $name, string $password, ?string $coverletter = null, ?string $cv = null): bool
    {
        $hashed_password = hash("sha512", $password);

        $stmt = $this->pdo->prepare("INSERT INTO Users (user_surname, user_email, user_name, user_password, user_coverletter, user_cv) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$surname, $email, $name, $hashed_password, $coverletter, $cv]);
    }

    public function updateUser(int $user_id, ?string $surname = null, ?string $email = null, ?string $name = null, ?string $password = null, ?string $coverletter = null, ?string $cv = null): bool
    {
        $fields = [];
        $params = [];

        if ($surname !== null) {
            $fields[] = "user_surname = ?";
            $params[] = $surname;
        }
        if ($email !== null) {
            $fields[] = "user_email = ?";
            $params[] = str_replace('&#64;', '@', $email);
        }
        if ($name !== null) {
            $fields[] = "user_name = ?";
            $params[] = $name;
        }
        if ($password !== null) {
            $fields[] = "user_password = ?";
            $params[] = hash("sha512", $password);
        }
        if ($coverletter !== null) {
            $fields[] = "user_coverletter = ?";
            $params[] = $coverletter;
        }
        if ($cv !== null) {
            $fields[] = "user_cv = ?";
            $params[] = $cv;
        }

        if (empty($fields))
            throw new Exception("Aucun champ à mettre à jour.");

        $params[] = $user_id;
        $sql = "UPDATE Users SET " . implode(", ", $fields) . " WHERE user_id = ?";
        return $this->pdo->prepare($sql)->execute($params);
    }

    public function deleteUser(int $user_id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM Users WHERE user_id = ?");
        return $stmt->execute([$user_id]);
    }

    // =========================================
    // SECTION 3 : LISTES UTILISATEURS
    // =========================================

    public function getPilotesList(): array
    {
        $sql = "SELECT * FROM Pilotes pi INNER JOIN Users u ON pi.user_id = u.user_id LEFT JOIN Promotions p ON p.pilote_id = pi.pilote_id ORDER BY p.promotion_name ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudentsList(string $role, int $user_id): array
    {
        $sql = "SELECT su.*, s.student_id, p.promotion_name, p.promotion_desc, pu.user_name AS pilote_name, pu.user_surname AS pilote_surname FROM Students s INNER JOIN Users su ON s.user_id = su.user_id INNER JOIN Promotions p ON s.promotion_id = p.promotion_id INNER JOIN Pilotes pi ON p.pilote_id = pi.pilote_id INNER JOIN Users pu ON pi.user_id = pu.user_id";
        if ($role === 'pilote')
            $sql .= " WHERE pi.user_id = :user_id";
        $sql .= " ORDER BY p.promotion_name ASC LIMIT 100";

        $stmt = $this->pdo->prepare($sql);
        if ($role === 'pilote')
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudentsByPromotionOrPilot(?int $promotion_id = null, ?int $pilote_user_id = null): array
    {
        $sql = "SELECT su.*, s.student_id, p.promotion_name, p.promotion_desc, pu.user_name AS pilote_name, pu.user_surname AS pilote_surname FROM Students s INNER JOIN Users su ON s.user_id = su.user_id INNER JOIN Promotions p ON s.promotion_id = p.promotion_id INNER JOIN Pilotes pi ON p.pilote_id = pi.pilote_id INNER JOIN Users pu ON pi.user_id = pu.user_id WHERE 1=1";
        $params = [];
        if ($promotion_id) {
            $sql .= " AND p.promotion_id = :promotion_id";
            $params['promotion_id'] = $promotion_id;
        }
        if ($pilote_user_id) {
            $sql .= " AND pi.user_id = :pilote_user_id";
            $params['pilote_user_id'] = $pilote_user_id;
        }
        $sql .= " ORDER BY p.promotion_name ASC LIMIT 100";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================================
    // SECTION 4 : DÉTAIL UTILISATEUR AVEC PROMOTION
    // =========================================

    public function getUserById(int $user_id): ?array
    {
        $sql = "SELECT 
                    u.*, 
                    p.promotion_name, 
                    p.promotion_desc, 
                    pu.user_name AS pilote_name, 
                    pu.user_surname AS pilote_surname
                FROM Users u 
                LEFT JOIN Students s ON u.user_id = s.user_id 
                LEFT JOIN Promotions p ON s.promotion_id = p.promotion_id
                LEFT JOIN Pilotes pi ON p.pilote_id = pi.pilote_id
                LEFT JOIN Users pu ON pi.user_id = pu.user_id
                WHERE u.user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    // =========================================
    // SECTION 5 : UTILITAIRE
    // =========================================

    private function existsInTable(string $table, int $userId): bool
    {
        $sql = "SELECT COUNT(*) FROM {$table} WHERE user_id = :uid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':uid', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // =========================================
    // SECTION 6 : CRUD PILOTE
    // =========================================

    public function createPilote(int $user_id, int $promotion_id): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO Pilotes (user_id) VALUES (?)");
        $stmt->execute([$user_id]);
        $pilote_id = $this->pdo->lastInsertId();

        $stmt2 = $this->pdo->prepare("UPDATE Promotions SET pilote_id = ? WHERE promotion_id = ?");
        return $stmt2->execute([$pilote_id, $promotion_id]);
    }

    public function updatePilote(int $user_id, int $promotion_id): bool
    {
        $stmt = $this->pdo->prepare("SELECT pilote_id FROM Pilotes WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $pilote = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$pilote)
            return false;

        $stmt2 = $this->pdo->prepare("UPDATE Promotions SET pilote_id = ? WHERE promotion_id = ?");
        return $stmt2->execute([$pilote['pilote_id'], $promotion_id]);
    }

    public function deletePilote(int $user_id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM Pilotes WHERE user_id = ?");
        return $stmt->execute([$user_id]);
    }

    public function getPaginatedStudents(int $limit, int $offset): array
    {
        $sql = "SELECT su.*, s.student_id, p.promotion_name, p.promotion_desc, pu.user_name AS pilote_name, pu.user_surname AS pilote_surname
            FROM Students s
            INNER JOIN Users su ON s.user_id = su.user_id
            INNER JOIN Promotions p ON s.promotion_id = p.promotion_id
            INNER JOIN Pilotes pi ON p.pilote_id = pi.pilote_id
            INNER JOIN Users pu ON pi.user_id = pu.user_id
            ORDER BY p.promotion_name ASC
            LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginatedPilotes(int $limit, int $offset): array
    {
        $sql = "SELECT 
                u.*, 
                pi.pilote_id,
                p.promotion_name, 
                p.promotion_desc
            FROM Pilotes pi
            INNER JOIN Users u ON pi.user_id = u.user_id
            LEFT JOIN Promotions p ON pi.pilote_id = p.pilote_id
            ORDER BY p.promotion_name ASC
            LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}