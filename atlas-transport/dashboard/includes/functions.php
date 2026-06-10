<?php
// =============================================
// Fonctions utilitaires
// =============================================
require_once __DIR__ . '/../config/db.php';

/** Échappe une chaîne pour l'affichage HTML */
function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Statistiques globales du tableau de bord */
function getStats(PDO $pdo): array {
    $totalExpeditions = (int) $pdo->query("SELECT COUNT(*) FROM expeditions")->fetchColumn();
    $clientsActifs    = (int) $pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn();
    $enAttente        = (int) $pdo->query("SELECT COUNT(*) FROM expeditions WHERE statut = 'En attente'")->fetchColumn();
    $chiffreAffaires  = (float) $pdo->query("SELECT COALESCE(SUM(frais_transport),0) FROM expeditions")->fetchColumn();

    return [
        'total_expeditions' => $totalExpeditions,
        'clients_actifs'    => $clientsActifs,
        'en_attente'        => $enAttente,
        'chiffre_affaires'  => $chiffreAffaires,
    ];
}

/** Liste des expéditions récentes avec le nom du client */
function getExpeditionsRecentes(PDO $pdo, int $limit = 5): array {
    $stmt = $pdo->prepare("
        SELECT e.*, c.nom, c.prenom
        FROM expeditions e
        JOIN clients c ON c.id = e.client_id
        ORDER BY e.date_depart DESC, e.id DESC
        LIMIT :limit
    ");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/** Toutes les expéditions */
function getExpeditions(PDO $pdo): array {
    return $pdo->query("
        SELECT e.*, c.nom, c.prenom
        FROM expeditions e
        JOIN clients c ON c.id = e.client_id
        ORDER BY e.id DESC
    ")->fetchAll();
}

/** Tous les clients */
function getClients(PDO $pdo): array {
    return $pdo->query("SELECT * FROM clients ORDER BY id DESC")->fetchAll();
}

/**
 * Classe CSS du badge de statut.
 * 'En cours de route' and 'En attente' both use the orange badge — matching the screenshot.
 * Added 'En préparation' / 'Préparation' aliases as a safe fallback.
 */
function statutClass(string $statut): string {
    return match (true) {
        $statut === 'Livrée'                                        => 'badge-livre',
        $statut === 'En cours de route'                             => 'badge-transit',
        $statut === 'En attente'                                    => 'badge-attente',
        in_array($statut, ['En préparation', 'Préparation'], true)  => 'badge-preparation',
        default                                                     => 'badge-attente',
    };
}

/**
 * Libellé court affiché dans le badge.
 * FIX 1: 'En attente' was incorrectly returning 'PRÉPARATION' → corrected to 'EN ATTENTE'.
 * FIX 2: 'En cours de route' now returns 'EN COURS' to match the screenshot (was 'EN TRANSIT').
 */
function statutLabel(string $statut): string {
    return match (true) {
        $statut === 'Livrée'                                        => 'LIVRÉ',
        $statut === 'En cours de route'                             => 'EN COURS',
        $statut === 'En attente'                                    => 'EN ATTENTE',
        in_array($statut, ['En préparation', 'Préparation'], true)  => 'PRÉPARATION',
        default                                                     => strtoupper($statut),
    };
}

/** Formatte un montant en notation courte (ex: 2.4M, 8.4k) */
function formatMontant(float $montant): string {
    if ($montant >= 1_000_000) return rtrim(rtrim(number_format($montant / 1_000_000, 1), '0'), '.') . 'M';
    if ($montant >= 1_000)     return rtrim(rtrim(number_format($montant / 1_000, 1), '0'), '.') . 'k';
    return number_format($montant, 0, ',', ' ');
}