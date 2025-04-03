<?php

// Chargement des dépendances nécessaires
require_once(__DIR__ . '/../Core/DataBase.php');
require_once(__DIR__ . '/../Controllers/Login.php');
require_once(__DIR__ . '/../Models/Wishlist.php');

use Models\Wishlist;

// Vérification de la session
if (!isset($_SESSION['id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(["message" => "Vous devez être connecté."]);
    exit;
}

$user_id = $_SESSION['id'];
$wishlistModel = new Wishlist($conn);

$nbr_wishlist = $wishlistModel->getWishlistsCount();

// --- GESTION AJAX EN DELETE ---
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    $offer_id = $data['offer_id'] ?? null;

    if (!$offer_id) {
        http_response_code(400);
        echo json_encode(["message" => "ID de l'offre manquant."]);
        exit;
    }

    try {
        $wishlistModel->removeWishFromWishlist($user_id, $offer_id);
        echo json_encode(["message" => "Offre supprimée avec succès."]);
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["message" => "Erreur serveur : " . $e->getMessage()]);
        exit;
    }
}

// --- GESTION AJAX EN POST JSON (AJOUT / SUPPRESSION) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['CONTENT_TYPE'] === 'application/json') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    $offer_id = $data['offer_id'] ?? null;
    $action = $data['action'] ?? null;

    if (!$offer_id || !in_array($action, ['add', 'remove'])) {
        http_response_code(400);
        echo json_encode(["message" => "Paramètres invalides."]);
        exit;
    }

    try {
        if ($action === 'add') {
            $wishlistModel->addWishToWishlist($user_id, $offer_id);
            echo json_encode(["message" => "Offre ajoutée à la wishlist."]);
        } else {
            $wishlistModel->removeWishFromWishlist($user_id, $offer_id);
            echo json_encode(["message" => "Offre retirée de la wishlist."]);
        }
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["message" => "Erreur serveur : " . $e->getMessage()]);
        exit;
    }
}

// --- GESTION CLASSIQUE PAR FORMULAIRE HTML ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    $offer_id = $_POST['offer_id'] ?? null;

    if ($action === "add" && $offer_id) {
        $wishlistModel->addWishToWishlist($user_id, $offer_id);
    } elseif ($action === "remove" && $offer_id) {
        $wishlistModel->removeWishFromWishlist($user_id, $offer_id);
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// --- RÉCUPÉRATION DE LA WISHLIST (GET) ---
try {
    $wishlist = $wishlistModel->getWishlistsByUserId($user_id);
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
