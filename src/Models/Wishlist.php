<?php

namespace Models;

use PDO;
use PDOException;
use Exception;

class Wishlist {

    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getWishlistsByUserId($user_id)
    {
        try {
            $stmt = $this->pdo->prepare("
            SELECT w.*, o.*, c.*, l.*, ci.*, r.*
            FROM Wishlists w
            INNER JOIN Offers o ON w.offer_id = o.offer_id
            INNER JOIN Companies c ON o.company_id = c.company_id
            INNER JOIN Located l ON c.company_id = l.company_id
            INNER JOIN Cities ci ON l.city_id = ci.city_id
            INNER JOIN Regions r ON ci.region_id = r.region_id
            WHERE w.user_id = :user_id
            ");
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des candidatures, des offres et des entreprises : " . $e->getMessage());
        }
    }

    public function addWishToWishlist($user_id, $offer_id)
    {
        try {
            $stmt = $this->pdo->prepare("
            INSERT INTO Wishlists (user_id, offer_id)
            VALUES (:user_id, :offer_id)
            ");
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':offer_id', $offer_id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la création de la candidature : " . $e->getMessage());
        }
    }

    public function removeWishFromWishlist($user_id, $offer_id)
    {
        try {
            $stmt = $this->pdo->prepare("
            DELETE FROM Wishlists
            WHERE user_id = :user_id AND offer_id = :offer_id
            ");
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':offer_id', $offer_id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la suppression de la candidature : " . $e->getMessage());
        }
    }

    public function getWishlistsCount()
{
    try {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS wishlist_count FROM Wishlists");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['wishlist_count'] ?? 0;
    } catch (PDOException $e) {
        throw new Exception("Erreur lors de la récupération du nombre de wishlists : " . $e->getMessage());
    }
}

}

// // Tests
// // Récupérer la wishlist d'un utilisateur 2
// $pdo = new PDO('mysql:host=localhost;dbname=Internity', 'admin', 'mdp');
// $wishlist = new Wishlist($pdo);
// $user_id = 2;
// $wishlists = $wishlist->getWishlistsByUserId($user_id);
// var_dump($wishlists);

// // Ajouter une offre à la wishlist d'un utilisateur 2
// $user_id = 2;
// $offer_id = 2;
// $wishlist->addWishToWishlist($user_id, $offer_id);
// $wishlists = $wishlist->getWishlistsByUserId($user_id);
// var_dump($wishlists);

// // Supprimer une offre de la wishlist d'un utilisateur 2
// $user_id = 2;
// $offer_id = 2;
// $wishlist->removeWishFromWishlist($user_id, $offer_id);
// $wishlists = $wishlist->getWishlistsByUserId($user_id);
// var_dump($wishlists);
