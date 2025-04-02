<?php
// Chargement des dépendances nécessaires
require_once(__DIR__ . '/../Core/DataBase.php');
require_once(__DIR__ . '/../Controllers/Login.php');
require_once(__DIR__ . '/../Models/Offer.php');

use Models\Offer;

// Instanciation du modèle Offer
$offerModel = new Offer($conn);

// Récupération de la page actuelle
$current_page = basename($_SERVER['PHP_SELF']);

// SECTION 1 : Pagination des offres
$elements_par_page = 9; // Nombre d'offres à afficher par page
$page_actuelle = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1; // Numéro de la page actuelle
$offset = ($page_actuelle - 1) * $elements_par_page; // Calcul de l'offset

// Récupération des offres paginées et du nombre total d'offres
$offers = $offerModel->getPaginatedOffers($elements_par_page, $offset);
$total_offers = $offerModel->getTotalOffersCount();
$total_pages = ceil($total_offers / $elements_par_page); // Calcul du nombre total de pages

// SECTION 2 : Gestion des détails d'une offre spécifique
$needsOffer = in_array($current_page, ['Offer.php', 'Apply.php']);

if ($needsOffer) {
    if (isset($_GET['offer_id'])) {
        $offerId = intval($_GET['offer_id']); // Conversion en entier pour éviter les injections SQL

        // Chargement des détails de l'offre
        $offerDetails = $offerModel->getOfferById($offerId);
        if (!$offerDetails) {
            die("Offre non trouvée."); // Gestion d'erreur si l'offre n'existe pas
        }

        // Récupération des compétences et des détails des entreprises associés à l'offre
        $skills = $offerModel->getOfferSkills($offerId);
        $companiesDetails = $offerModel->getOffersCompanies($offerId);
    } else {
        die("ID de l'offre manquant."); // Gestion d'erreur si l'ID de l'offre est absent
    }
}

// SECTION 3 : Fonction utilitaire pour créer des slugs URL-friendly
/**
 * Crée un slug à partir d'une chaîne de caractères.
 *
 * @param string $string La chaîne à transformer.
 * @return string Le slug généré.
 */
function createSlug($string)
{
    $string = strtolower($string); // Convertir en minuscules
    $string = preg_replace('/\s+/', '-', $string); // Remplacer les espaces par des tirets
    $string = preg_replace('/[^a-z0-9\-]/', '', $string); // Supprimer les caractères spéciaux
    return $string;
}

// SECTION 4 : Création d'une nouvelle offre
if ($current_page === 'create/Offer.php' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification des données POST
    if (isset($_POST['title'], $_POST['description'], $_POST['company_id'], $_POST['skills'])) {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $companyId = intval($_POST['company_id']);
        $skills = array_map('trim', $_POST['skills']); // Supposons que les compétences sont envoyées sous forme de tableau

        // Validation des données
        if (!empty($title) && !empty($description) && $companyId > 0 && !empty($skills)) {
            // Création de l'offre
            $offerId = $offerModel->createOffer($title, $description, $companyId);

            if ($offerId) {
                // Ajout des compétences associées à l'offre
                foreach ($skills as $skill) {
                    $offerModel->addSkillToOffer($offerId, $skill);
                }

                // Redirection ou message de succès
                header('Location: /offers?success=1');
                exit;
            } else {
                die("Erreur lors de la création de l'offre.");
            }
        } else {
            die("Données invalides.");
        }
    } else {
        die("Données POST manquantes.");
    }
}
