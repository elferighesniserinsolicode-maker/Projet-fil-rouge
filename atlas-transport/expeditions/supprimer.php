<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: ../connexion.php"); exit; }
require '../db.php';
$pdo->prepare("DELETE FROM expeditions WHERE id=?")->execute([intval($_GET['id'] ?? 0)]);
header("Location: liste.php?success=1");
exit;
?>
