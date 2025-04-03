<?php
require_once('../../src/Controllers/Login.php');
require_once('../../src/Controllers/CheckAuth.php');
require_once('../../src/Controllers/User.php');

require_once(__DIR__ . '/../../src/Core/DataBase.php');
require_once(__DIR__ . '/../../src/Models/User.php');

use Models\User;

$user_id = $_SESSION['id'] ?? 0;
$role = $_SESSION['role'] ?? 'inconnu';

$userModel = new User($conn);
$pilotes = $userModel->getPilotesList();
$students = $userModel->getStudentsList($role, $user_id);

$error_message = '';
if (empty($students)) {
    $error_message = "Pas d'étudiants";
}
?>