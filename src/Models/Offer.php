<?php

namespace Models;

use PDO;
use PDOException;
use Exception;

class Offer
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllOffers()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM Offers");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des offres : " . $e->getMessage());
        }
    }

    public function getOfferById($offer_id)
    {
        try {
            if (!is_numeric($offer_id)) {
                throw new Exception("ID invalide.");
            }
            $stmt = $this->pdo->prepare("SELECT * FROM Offers WHERE offer_id = :offer_id");
            $stmt->bindValue(':offer_id', $offer_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération de l'offre : " . $e->getMessage());
        }
    }
    public function createOffer($data)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO Offers (offer_title, offer_desc, offer_salary, offer_start, offer_end, offer_countapply, company_id)
                VALUES (:offer_title, :offer_desc, :offer_salary, :offer_start, :offer_end, :offer_countapply, :company_id)
            ");
            $stmt->bindValue(':offer_title', $data['offer_title']);
            $stmt->bindValue(':offer_desc', $data['offer_desc']);
            $stmt->bindValue(':offer_salary', $data['offer_salary']);
            $stmt->bindValue(':offer_start', $data['offer_start']);
            $stmt->bindValue(':offer_end', $data['offer_end']);
            $stmt->bindValue(':offer_countapply', $data['offer_countapply'] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':company_id', $data['company_id'], PDO::PARAM_INT);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la création de l'offre : " . $e->getMessage());
        }
    }

    public function updateOffer($offer_id, $data)
    {
        try {
            if (!is_numeric($offer_id)) {
                throw new Exception("ID invalide.");
            }
            $stmt = $this->pdo->prepare("
                UPDATE Offers 
                SET offer_title = :offer_title, 
                    offer_desc = :offer_desc, 
                    offer_salary = :offer_salary, 
                    offer_start = :offer_start, 
                    offer_end = :offer_end, 
                    offer_countapply = :offer_countapply, 
                    company_id = :company_id
                WHERE offer_id = :offer_id
            ");
            $stmt->bindValue(':offer_title', $data['offer_title']);
            $stmt->bindValue(':offer_desc', $data['offer_desc']);
            $stmt->bindValue(':offer_salary', $data['offer_salary']);
            $stmt->bindValue(':offer_start', $data['offer_start']);
            $stmt->bindValue(':offer_end', $data['offer_end']);
            $stmt->bindValue(':offer_countapply', $data['offer_countapply'], PDO::PARAM_INT);
            $stmt->bindValue(':company_id', $data['company_id'], PDO::PARAM_INT);
            $stmt->bindValue(':offer_id', $offer_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la mise à jour de l'offre : " . $e->getMessage());
        }
    }

    public function deleteOffer($offer_id)
    {
        try {
            if (!is_numeric($offer_id)) {
                throw new Exception("ID invalide.");
            }
            $stmt = $this->pdo->prepare("DELETE FROM Offers WHERE offer_id = :offer_id");
            $stmt->bindValue(':offer_id', $offer_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la suppression de l'offre : " . $e->getMessage());
        }
    }

    public function getOfferSkills($offer_id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT s.skills_id, s.skills_name 
                                             FROM Details d
                                             JOIN Skills s ON d.skills_id = s.skills_id
                                             WHERE d.offer_id = :offer_id");
            $stmt->bindParam(':offer_id', $offer_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des compétences : " . $e->getMessage());
        }
    }

    public function getPaginatedOffers($limit, $offset)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM Offers LIMIT :limit OFFSET :offset");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des offres paginées : " . $e->getMessage());
        }
    }

    public function getTotalOffersCount()
    {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM Offers");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors du comptage des offres : " . $e->getMessage());
        }
    }
}

