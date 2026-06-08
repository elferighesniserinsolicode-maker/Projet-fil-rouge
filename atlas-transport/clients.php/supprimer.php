<?php
session_start();
if (!isset($_SESSION['admin'])) header("Location: ../connexion.php");
require '../config.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
$stmt->execute([$id]);
header("Location: liste.php");
exit;
?>