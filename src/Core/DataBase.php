<?php

// Inclure le fichier de configuration
require_once __DIR__ . '/Config.php';


try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully\n";
  } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
  

