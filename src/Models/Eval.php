<?php

namespace Models;

use PDO;
use PDOException;

class Evaluations
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function addEvaluation(int $userId, int $companyId, int $rate, string $comment): bool
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO Evaluations (user_id, company_id, feedback_rate, feedback_comment)
                VALUES (:userId, :companyId, :rate, :comment)
            ");
            $stmt->execute([
                ':userId' => $userId,
                ':companyId' => $companyId,
                ':rate' => $rate,
                ':comment' => $comment
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateCompanyAverage(int $companyId): bool
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT AVG(feedback_rate) AS avgRate
                FROM Evaluations
                WHERE company_id = :companyId
            ");
            $stmt->execute([':companyId' => $companyId]);
            $average = $stmt->fetchColumn();

            $update = $this->pdo->prepare("
                UPDATE Companies
                SET company_averagerate = :avg
                WHERE company_id = :companyId
            ");
            $update->execute([':avg' => $average, ':companyId' => $companyId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}