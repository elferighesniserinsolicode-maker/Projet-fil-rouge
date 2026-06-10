<?php
// =============================================
// Atlas Transport Maroc — Connexion base de données
// =============================================
// Adaptez ces valeurs à votre environnement (XAMPP/WAMP/MAMP).

$DB_HOST = 'localhost';
$DB_NAME = 'atlas_transport';
$DB_USER = 'root';
$DB_PASS = '';      // mot de passe MySQL (vide par défaut sous XAMPP)
$DB_CHARSET = 'utf8';

try {
    $dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=$DB_CHARSET";
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}