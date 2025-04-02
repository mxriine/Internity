<?php
require_once(__DIR__ . '/../Core/DataBase.php');
require_once(__DIR__ . '/../Models/Offer.php');

use Models\Offer;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$offerModel = new Offer($conn);

// Chemins utiles
$current_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$current_file = basename($current_path);

// Fonction utilitaire
function createSlug($string)
{
    $string = strtolower($string);
    $string = preg_replace('/\s+/', '-', $string);
    $string = preg_replace('/[^a-z0-9\-]/', '', $string);
    return $string;
}

// =========================================
// SECTION 1 : Création d'une offre (POST)
// =========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['offer_title'] ?? '');
    $description = trim($_POST['offer_desc'] ?? '');
    $salary = floatval($_POST['offer_salary'] ?? 0.00);
    $start = $_POST['offer_start'] ?? '';
    $end = $_POST['offer_end'] ?? '';
    $count = intval($_POST['offer_countapply'] ?? 0);
    $companyId = intval($_POST['company_id'] ?? 1);

    if ($title && $description && $start && $end && $companyId > 0) {
        try {
            $offerData = [
                'offer_title' => $title,
                'offer_desc' => $description,
                'offer_salary' => $salary,
                'offer_start' => $start,
                'offer_end' => $end,
                'offer_countapply' => $count,
                'company_id' => $companyId,
            ];

            $offerId = $offerModel->createOffer($offerData);

            if ($offerId) {
                header('Location: /vues/Offer.php?offer_id=' . $offerId . '&title=' . createSlug($title));
                exit;
            } else {
                die("Erreur lors de la création de l'offre.");
            }
        } catch (Exception $e) {
            die("Erreur serveur : " . $e->getMessage());
        }
    } else {
        die("Tous les champs requis doivent être remplis.");
    }
}

// =========================================
// SECTION 2 : Pagination des offres (/Discover.php)
// =========================================
if ($current_file === 'Discover.php') {
    $elements_par_page = 9;
    $page_actuelle = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
    $offset = ($page_actuelle - 1) * $elements_par_page;

    $offers = $offerModel->getPaginatedOffers($elements_par_page, $offset);
    $total_offers = $offerModel->getTotalOffersCount();
    $total_pages = ceil($total_offers / $elements_par_page);
}

// =========================================
// SECTION 3 : Détails d'une offre (/Offer.php ou /Apply.php)
// =========================================
if (in_array($current_file, ['Offer.php', 'Apply.php'])) {
    if (isset($_GET['offer_id'])) {
        $offerId = intval($_GET['offer_id']);

        $offerDetails = $offerModel->getOfferById($offerId);
        if (!$offerDetails) {
            die("Offre non trouvée.");
        }

        $skills = $offerModel->getOfferSkills($offerId);
        $companiesDetails = $offerModel->getOffersCompanies($offerId);
    } else {
        die("ID de l'offre manquant.");
    }
}

// =========================================
// SECTION 4 : Modification d'une offre (POST + ?edit=1)
// =========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['edit']) && isset($_POST['offer_id'])) {
    $offerId = intval($_POST['offer_id']);
    $title = trim($_POST['offer_title'] ?? '');
    $description = trim($_POST['offer_desc'] ?? '');
    $salary = floatval($_POST['offer_salary'] ?? 0.00);
    $start = $_POST['offer_start'] ?? '';
    $end = $_POST['offer_end'] ?? '';
    $count = intval($_POST['offer_countapply'] ?? 0);
    $companyId = intval($_POST['company_id'] ?? 1);

    if ($offerId > 0 && $title && $description && $start && $end && $companyId > 0) {
        try {
            $offerData = [
                'offer_title' => $title,
                'offer_desc' => $description,
                'offer_salary' => $salary,
                'offer_start' => $start,
                'offer_end' => $end,
                'offer_countapply' => $count,
                'company_id' => $companyId,
            ];

            $success = $offerModel->updateOffer($offerId, $offerData);

            if ($success) {
                header('Location: /vues/Offer.php?offer_id=' . $offerId . '&title=' . createSlug($title));
                exit;
            } else {
                die("Échec de la mise à jour de l'offre.");
            }
        } catch (Exception $e) {
            die("Erreur serveur : " . $e->getMessage());
        }
    } else {
        die("Tous les champs sont obligatoires pour modifier l'offre.");
    }
}

// =========================================
// SECTION 5 : Suppression d'une offre (POST)
// =========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['delete']) && isset($_POST['offer_id'])) {
    $offerId = intval($_POST['offer_id']);

    if ($offerId > 0) {
        try {
            $deleted = $offerModel->deleteOffer($offerId);

            if ($deleted) {
                header('Location: /vues/Offers.php?deleted=1');
                exit;
            } else {
                die("Échec de la suppression de l'offre.");
            }
        } catch (Exception $e) {
            die("Erreur serveur : " . $e->getMessage());
        }
    } else {
        die("ID d'offre invalide.");
    }
}
