<?php
// Chargement des dépendances nécessaires
require_once(__DIR__ . '/../Core/DataBase.php');
require_once(__DIR__ . '/../Controllers/Login.php');
require_once(__DIR__ . '/../Models/Application.php');

use Models\Application;

if (!isset($_SESSION['id'])) {
    die("Vous devez être connecté pour postuler.");
}

$current_page = basename($_SERVER['PHP_SELF']);

$user_id = $_SESSION['id'];
$offer_id = $_POST['offer_id'] ?? null;
$message = $_POST['message'] ?? null;

if ($current_page === 'Apply.php' && !$offer_id) {
    die("Aucune offre spécifiée.");
}

// Si formulaire soumis et fichiers présents
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["cv"]) && isset($_FILES["coverletter"])) {
    try {
        $cv = $_FILES["cv"];
        $coverLetter = $_FILES["coverletter"];
        $uploadDir = __DIR__ . "/../../assets/uploads/";

        // Fonction de traitement d'upload
        function handleFileUpload($file, $uploadDir, $allowedTypes, $maxSize, $fileLabel)
        {
            if ($file["error"] !== UPLOAD_ERR_OK) {
                throw new Exception("Erreur lors du téléversement du fichier $fileLabel.");
            }

            $fileType = mime_content_type($file["tmp_name"]);
            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception("Format non autorisé pour $fileLabel.");
            }

            if ($file["size"] > $maxSize) {
                throw new Exception("Le fichier $fileLabel dépasse la taille limite.");
            }

            $fileName = pathinfo($file["name"], PATHINFO_FILENAME);
            $fileExtension = pathinfo($file["name"], PATHINFO_EXTENSION);
            $fileName = preg_replace("/[^a-zA-Z0-9_-]/", "_", $fileName);
            $fileName = $fileName . "_" . time() . "." . $fileExtension;

            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                    throw new Exception("Erreur lors de la création du dossier d'upload.");
                }
            }

            $filePath = $uploadDir . $fileName;
            if (!move_uploaded_file($file["tmp_name"], $filePath)) {
                throw new Exception("Erreur lors du déplacement du fichier $fileLabel.");
            }

            return $filePath;
        }

        $allowedTypes = [
            "application/pdf",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/rtf",
            "image/jpeg",
            "image/png"
        ];
        $maxSize = 2 * 1024 * 1024; // 2 Mo

        // Upload des fichiers
        $CVPath = handleFileUpload($cv, $uploadDir, $allowedTypes, $maxSize, "CV");
        $coverLetterPath = handleFileUpload($coverLetter, $uploadDir, $allowedTypes, $maxSize, "Lettre de motivation");

        // Enregistrement en base
        $applicationModel = new Application($conn);
        $data = [
            'user_id' => $user_id,
            'offer_id' => $offer_id,
            'apply_date' => date('Y-m-d H:i:s'),
            'apply_cv' => $CVPath,
            'apply_coverletter' => $coverLetterPath,
            'apply_message' => $message,
            'apply_status' => 'En cours'
        ];

        $result = $applicationModel->createApplication($data);

        if ($result) {
            header("Location: /vues/Profil.php?success=1");
            exit;
        } else {
            echo "Erreur lors de l'enregistrement de la candidature.";
        }

    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
} elseif ($current_page === 'Apply.php') {
    // Si la page est "Apply.php" mais que le formulaire n'est pas soumis
    echo "Veuillez soumettre le formulaire.";
}

// Initialiser le modèle
$applicationModel = new Application($conn);
$applications = $applicationModel->getApplicationsByUserId($user_id);

?>