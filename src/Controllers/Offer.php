<?php
require_once(__DIR__ . '/../Core/DataBase.php');
require_once(__DIR__ . '/../Controllers/Login.php');
require_once(__DIR__ . '/../Models/Offer.php');

use Models\Offer;

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

//
// =========================================
// SECTION 1 : SUPPRESSION d'une offre (?delete=1)
// =========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['delete']) && isset($_POST['offer_id'])) {
    $offerId = intval($_POST['offer_id']);

    if ($offerId > 0) {
        try {
            $deleted = $offerModel->deleteOffer($offerId);

            if ($deleted) {
                header('Location: /vues/dashboard/Offers.php');
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

//
// =========================================
// SECTION 2 : MODIFICATION d'une offre (?edit=1)
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
}

//
// =========================================
// SECTION 3 : CRÉATION d'une offre (POST simple)
// =========================================
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

//
// =========================================
// SECTION 4 : Pagination des offres (/Discover.php ou /Offers.php)
// =========================================
if (in_array($current_file, ['Discover.php', 'Offers.php'])) {
    $elements_par_page = 9;
    $page_actuelle = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
    $offset = ($page_actuelle - 1) * $elements_par_page;

    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $location = isset($_GET['location']) ? trim($_GET['location']) : '';
    $offers = $offerModel->getPaginatedOffers($elements_par_page, $offset, $search, $location);
    $total_offers = $offerModel->getTotalPaginatedOffersCount($elements_par_page, $search, $location);
    $total_pages = ceil($total_offers / $elements_par_page); // Calcul du nombre total de pages
}

//
// =========================================
// SECTION 5 : Détails d'une offre (/Offer.php ou /Apply.php)
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