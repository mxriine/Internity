<?php

// Chargement des dépendances nécessaires
require_once(__DIR__ . '/../Core/DataBase.php');
require_once(__DIR__ . '/../Controllers/Login.php');
require_once(__DIR__ . '/../Models/Wishlist.php');

use Models\Wishlist;

if (!isset($_SESSION['id'])) {
    die("Vous devez être connecté pour gérer votre wishlist.");
}

$user_id = $_SESSION['id'];
$action = $_POST['action'] ?? null;
$offer_id = $_POST['offer_id'] ?? null;

try {
    $wishlistModel = new Wishlist($conn);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if ($action === "add" && $offer_id) {
            // Ajouter une offre à la wishlist
            $wishlistModel->addWishToWishlist($user_id, $offer_id);
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        } elseif ($action === "remove" && $offer_id) {
            // Supprimer une offre de la wishlist
            $wishlistModel->removeWishFromWishlist($user_id, $offer_id);
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            echo "Action ou offre non spécifiée.";
        }
    } else {
        // Récupérer les offres de la wishlist
        $wishlist = $wishlistModel->getWishlistsByUserId($user_id);
    }
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>