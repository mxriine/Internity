<?php
require_once(__DIR__ . '/../Core/DataBase.php');
require_once(__DIR__ . '/../Controllers/Login.php');
require_once(__DIR__ . '/../Models/Promotion.php');

use Models\Promotion;

$promotionModel = new Promotion($conn);

$promotions = $promotionModel->getPromotion();