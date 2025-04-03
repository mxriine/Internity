<?php
require_once(__DIR__ . '/../Core/DataBase.php');
require_once(__DIR__ . '/../Controllers/Login.php');
require_once(__DIR__ . '/../Models/Companies.php');
require_once(__DIR__ . '/../Models/Offer.php');
require_once(__DIR__ . '/../Models/User.php');
require_once(__DIR__ . '/../Models/Wishlist.php');
require_once(__DIR__ . '/../Models/Application.php');

use Models\Companies;

$companiesModel = new Companies($conn);

$moyenne = $companiesModel->getCompanyAverage();
$total_companies = $companiesModel->getTotalCompaniesCount();

use Models\User;

$usersModel = new User($conn);

$total_users = $usersModel->getTotalStudentsCount();
$total_pilotes = $usersModel->getTotalPiloteCount();

use Models\Offer;

$offersModel = new Offer($conn);

$total_offers = $offersModel->getTotalOffersCount();

use Models\Wishlist;

$wishlistModel = new Wishlist($conn);

$total_wishlists = $wishlistModel->getWishlistsCount();

use Models\Application;

$applicationModel = new Application($conn);

$total_apply = $applicationModel->getApplicationsCount();

$apply_pending = $applicationModel->getPendingApplicationsCount();
$apply_accepted = $applicationModel->getAcceptedApplicationsCount();
$apply_rejected = $applicationModel->getRejectedApplicationsCount();



