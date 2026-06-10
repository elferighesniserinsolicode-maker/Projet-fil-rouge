<?php
// =============================================
// Actions CRUD — Clients
// =============================================
require_once __DIR__ . '/../includes/functions.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'create':
            $stmt = $pdo->prepare("INSERT INTO clients (nom, prenom, telephone, email, adresse) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                trim($_POST['nom'] ?? ''),
                trim($_POST['prenom'] ?? ''),
                trim($_POST['telephone'] ?? ''),
                trim($_POST['email'] ?? ''),
                trim($_POST['adresse'] ?? ''),
            ]);
            break;

        case 'update':
            $stmt = $pdo->prepare("UPDATE clients SET nom=?, prenom=?, telephone=?, email=?, adresse=? WHERE id=?");
            $stmt->execute([
                trim($_POST['nom'] ?? ''),
                trim($_POST['prenom'] ?? ''),
                trim($_POST['telephone'] ?? ''),
                trim($_POST['email'] ?? ''),
                trim($_POST['adresse'] ?? ''),
                (int) ($_POST['id'] ?? 0),
            ]);
            break;

        case 'delete':
            $stmt = $pdo->prepare("DELETE FROM clients WHERE id=?");
            $stmt->execute([(int) ($_GET['id'] ?? 0)]);
            break;
    }
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}

header('Location: ../clients.php');
exit;