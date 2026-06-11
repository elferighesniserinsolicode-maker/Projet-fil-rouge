<?php
session_start();
if (isset($_SESSION['admin'])) { header("Location: dashboard.php"); exit; }

$erreur = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $mdp   = trim($_POST['mdp']   ?? '');
    if ($login === 'admin' && $mdp === 'admin123') {
        $_SESSION['admin'] = true;
        header("Location: dashboard/index.php"); exit;
    } else {
        $erreur = "Identifiant ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Atlas Transport</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">

<nav class="navbar">
    <a href="index.php" class="brand">🚚 Atlas Transport Maroc</a>
    <div class="nav-links">
        <span style="color:rgba(255,255,255,0.7);font-size:13px">Espace Administrateur</span>
    </div>
</nav>

<div class="login-main">
    <div class="login-bg-img"></div>
    <div class="login-card">
        <div class="login-icon">🔒</div>
        <h2>Connexion Administrateur</h2>
        <p class="sub">Accès réservé à l'administrateur</p>

        <?php if ($erreur): ?>
            <div class="alert-error"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form method="POST" style="text-align:left">
            <div class="form-group">
                <label>Nom d'utilisateur</label>
                <input type="text" name="login" placeholder="admin" required
                       value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="mdp" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary"
                    style="width:100%;justify-content:center;margin-top:6px">
                Se connecter →
            </button>
        </form>
        <p style="margin-top:20px;font-size:12px;color:#94A3B8">
         
        </p>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>
