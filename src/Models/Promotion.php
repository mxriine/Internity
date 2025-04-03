<?php

namespace Models;

use PDO;
use PDOException;
use Exception;

class Promotion
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // =========================================
    // SECTION 1 : COMPTEURS UTILISATEURS
    // =========================================

    public function getPromotion(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM Promotions");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}