<?php
require_once(__DIR__ . '/../Core/DataBase.php');
require_once(__DIR__ . '/../Controllers/Login.php');
require_once(__DIR__ . '/../Models/User.php');

use Models\User;

$userModel = new User($conn);

// Infos session
$user_id = $_SESSION['id'] ?? 0;
$role = $_SESSION['role'] ?? 'inconnu';

// Chemin actuel
$current_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$current_file = basename($current_path);

// Récupération des pilotes si admin
$pilotes = ($role === 'admin') ? $userModel->getPilotesList() : [];

// Filtres éventuels
$promotion_id = isset($_GET['promotion_id']) ? intval($_GET['promotion_id']) : null;
$filtre_pilote = isset($_GET['pilote_id']) ? intval($_GET['pilote_id']) : null;

// Récupération des étudiants
if ($promotion_id || $filtre_pilote) {
    $students = $userModel->getStudentsByPromotionOrPilot($promotion_id, $filtre_pilote);
} else {
    $students = $userModel->getStudentsList($role, $user_id);
}

// =========================================
// Pagination des Etudiants
// =========================================
if (in_array($current_file, ['Students.php'])) {
    $limit = 5;
    $page_actuelle = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $offset = ($page_actuelle - 1) * $limit;

    $total_etudiants = $userModel->getUsersByRole('student');
    $total_pages = ceil($total_etudiants / $limit);

    $students = $userModel->getPaginatedStudents($limit, $offset);
}

// =========================================
// Pagination des Pilotes
// =========================================
if (in_array($current_file, ['Pilotes.php'])) {
    $limit = 5;
    $page_actuelle = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $offset = ($page_actuelle - 1) * $limit;

    $total_pilotes = $userModel->getUsersByRole('pilote');
    $total_pages = ceil($total_pilotes / $limit);

    $pilotes = $userModel->getPaginatedPilotes($limit, $offset);
}


// =========================================
// SUPPRESSION UTILISATEUR
// =========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['delete']) && isset($_POST['user_id'])) {
    $id = intval($_POST['user_id']);

    if ($id > 0) {
        try {
            $deleted = $userModel->deleteUser($id);
            if ($deleted) {
                header('Location: /vues/dashboard/Students.php');
                exit;
            } else {
                die("Suppression impossible.");
            }
        } catch (Exception $e) {
            die("Erreur serveur : " . $e->getMessage());
        }
    }
}

// =========================================
// MODIFICATION UTILISATEUR (student ou pilote)
// =========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['edit'])) {
    $id = intval($_POST['user_id'] ?? 0);
    $surname = trim($_POST['user_surname'] ?? '');
    $name = trim($_POST['user_name'] ?? '');
    $email = trim($_POST['user_email'] ?? '');
    $password = trim($_POST['user_password'] ?? '');
    $promotion_id = isset($_POST['promotion_id']) ? intval($_POST['promotion_id']) : null;

    try {
        $updated = $userModel->updateUser($id, $surname, $email, $name, $password !== '' ? $password : null);

        if (isset($_POST['pilote_id'])) {
            $userModel->updatePilote($id, $promotion_id);
        }

        if ($updated) {
            header('Location: /vues/dashboard/Students.php');
            exit;
        } else {
            die("Mise à jour échouée.");
        }
    } catch (Exception $e) {
        die("Erreur serveur : " . $e->getMessage());
    }
}

// =========================================
// CRÉATION UTILISATEUR (par défaut)
// =========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_GET['edit']) && !isset($_GET['delete'])) {
    $surname = trim($_POST['user_surname'] ?? '');
    $name = trim($_POST['user_name'] ?? '');
    $email = trim($_POST['user_email'] ?? '');
    $password = trim($_POST['user_password'] ?? '');
    $promotion_id = isset($_POST['promotion_id']) ? intval($_POST['promotion_id']) : null;

    if ($surname && $name && $email && $password) {
        try {
            $created = $userModel->createUser($surname, $email, $name, $password);
            if ($created) {
                $newUserId = $conn->lastInsertId();

                if (isset($_POST['pilote_id'])) {
                    $userModel->createPilote($newUserId, $promotion_id);
                } else {
                    if ($role === 'admin' && isset($_POST['pilote_id'])) {
                        $piloteId = intval($_POST['pilote_id']);
                        $stmt = $conn->prepare("SELECT promotion_id FROM Promotions WHERE pilote_id = (SELECT pilote_id FROM Pilotes WHERE user_id = ?)");
                        $stmt->execute([$piloteId]);
                        $promo = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($promo) {
                            $stmt = $conn->prepare("INSERT INTO Students (user_id, promotion_id) VALUES (?, ?)");
                            $stmt->execute([$newUserId, $promo['promotion_id']]);
                        }
                    } elseif ($role === 'pilot') {
                        $stmt = $conn->prepare("SELECT p.promotion_id FROM Promotions p INNER JOIN Pilotes pi ON p.pilote_id = pi.pilote_id WHERE pi.user_id = ?");
                        $stmt->execute([$user_id]);
                        $promo = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($promo) {
                            $stmt = $conn->prepare("INSERT INTO Students (user_id, promotion_id) VALUES (?, ?)");
                            $stmt->execute([$newUserId, $promo['promotion_id']]);
                        }
                    }
                }

                header('Location: /vues/dashboard/Students.php');
                exit;
            } else {
                die("Erreur lors de la création.");
            }
        } catch (Exception $e) {
            die("Erreur serveur : " . $e->getMessage());
        }
    } else {
        die("Tous les champs sont requis.");
    }
}

// =========================================
// DÉTAILS D'UN UTILISATEUR (student ou pilote)
// =========================================
if (($current_file === 'Student.php' && isset($_GET['student_id'])) || ($current_file === 'Pilote.php' && isset($_GET['pilote_id']))) {
    $id = ($current_file === 'Student.php') ? intval($_GET['student_id']) : intval($_GET['pilote_id']);
    $type = ($current_file === 'Student.php') ? 'student' : 'pilot';

    if ($id > 0) {
        $userDetails = $userModel->getUserById($id);
        if (!$userDetails) {
            die(ucfirst($type) . " non trouvé.");
        }
    } else {
        die("ID de l'utilisateur invalide.");
    }
}
