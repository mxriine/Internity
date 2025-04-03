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

    //
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
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) AS student_count FROM Students");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['student_count'] ?? 0;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération du nombre de students : " . $e->getMessage());
        }
    }

    public function getTotalPiloteCount(): int
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) AS pilote_count FROM Pilotes");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['pilote_count'] ?? 0;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération du nombre de pilotes : " . $e->getMessage());
        }
    }

    public function getStudentsList(string $role, int $user_id): array
    {
        if ($role === 'pilote') {
            // Si c'est un pilote, on récupère uniquement ses étudiants
            $sql = "
            SELECT 
                su.*,                          
                s.student_id,
                p.promotion_name,
                p.promotion_desc,
                pu.user_name AS pilote_name,
                pu.user_surname AS pilote_surname
            FROM Students s
            INNER JOIN Users su ON s.user_id = su.user_id
            INNER JOIN Promotions p ON s.promotion_id = p.promotion_id
            INNER JOIN Pilotes pi ON p.pilote_id = pi.pilote_id
            INNER JOIN Users pu ON pi.user_id = pu.user_id
            WHERE pi.user_id = :user_id
            ORDER BY p.promotion_name ASC
            LIMIT 100
        ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        } else {
            // Pour les admins ou autres rôles
            $sql = "
            SELECT 
                su.*,                          
                s.student_id,
                p.promotion_name,
                p.promotion_desc,
                pu.user_name AS pilote_name,
                pu.user_surname AS pilote_surname
            FROM Students s
            INNER JOIN Users su ON s.user_id = su.user_id
            INNER JOIN Promotions p ON s.promotion_id = p.promotion_id
            INNER JOIN Pilotes pi ON p.pilote_id = pi.pilote_id
            INNER JOIN Users pu ON pi.user_id = pu.user_id
            ORDER BY p.promotion_name ASC
            LIMIT 100
        ";
            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    //
    // =========================================
    // SECTION 2 : CRUD UTILISATEURS
    // =========================================

    public function createUser(string $surname, string $email, string $name, string $password, ?string $coverletter = null, ?string $cv = null): bool
    {
        $hashed_password = hash("sha512", $password);

        $stmt = $this->pdo->prepare("
            INSERT INTO Users (user_surname, user_email, user_name, user_password, user_coverletter, user_cv)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
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

        if (empty($fields)) {
            throw new Exception("Aucun champ à mettre à jour.");
        }

        $params[] = $user_id;

        $sql = "UPDATE Users SET " . implode(", ", $fields) . " WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($params);
    }

    public function deleteUser(int $user_id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM Users WHERE user_id = ?");
        return $stmt->execute([$user_id]);
    }

    //
    // =========================================
    // SECTION 3 : LISTES UTILISATEURS
    // =========================================

    public function getPilotesList(): array
    {
        $sql = "
            SELECT * FROM Pilotes pi
            INNER JOIN Users u ON pi.user_id = u.user_id
            LEFT JOIN Promotions p ON p.pilote_id = u.user_id
            ORDER BY p.promotion_name ASC LIMIT 100
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    //
    // =========================================
    // SECTION 4 : DÉTAIL D’UN UTILISATEUR
    // =========================================

    public function getUserById(int $userId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return null;
        }

        // Détermination du rôle
        $role = null;

        if ($this->existsInTable('students', $userId)) {
            $role = 'Etudiant';
        } elseif ($this->existsInTable('pilotes', $userId)) {
            $role = 'Pilote';
        } elseif ($this->existsInTable('admins', $userId)) {
            $role = 'Admin';
        }

        $user['user_role'] = $role;

        return $user;
    }

    //
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
}
