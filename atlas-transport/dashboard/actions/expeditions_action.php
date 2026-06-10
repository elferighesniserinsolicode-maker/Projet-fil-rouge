<?php
// =============================================
// Actions CRUD — Expéditions
// =============================================
require_once __DIR__ . '/../includes/functions.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'create':
            $stmt = $pdo->prepare("INSERT INTO expeditions (reference, client_id, ville_depart, ville_arrivee, poids, frais_transport, date_depart, statut) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                trim($_POST['reference'] ?? ''),
                (int) ($_POST['client_id'] ?? 0),
                trim($_POST['ville_depart'] ?? ''),
                trim($_POST['ville_arrivee'] ?? ''),
                (float) ($_POST['poids'] ?? 0),
                (float) ($_POST['frais_transport'] ?? 0),
                $_POST['date_depart'] ?: null,
                $_POST['statut'] ?? 'En attente',
            ]);
            break;

        case 'update':
            $stmt = $pdo->prepare("UPDATE expeditions SET reference=?, client_id=?, ville_depart=?, ville_arrivee=?, poids=?, frais_transport=?, date_depart=?, statut=? WHERE id=?");
            $stmt->execute([
                trim($_POST['reference'] ?? ''),
                (int) ($_POST['client_id'] ?? 0),
                trim($_POST['ville_depart'] ?? ''),
                trim($_POST['ville_arrivee'] ?? ''),
                (float) ($_POST['poids'] ?? 0),
                (float) ($_POST['frais_transport'] ?? 0),
                $_POST['date_depart'] ?: null,
                $_POST['statut'] ?? 'En attente',
                (int) ($_POST['id'] ?? 0),
            ]);
            break;

        case 'delete':
            $stmt = $pdo->prepare("DELETE FROM expeditions WHERE id=?");
            $stmt->execute([(int) ($_GET['id'] ?? 0)]);
            break;
    }
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}

header('Location: ../expeditions.php');
exit;