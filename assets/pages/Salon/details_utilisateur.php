<?php
session_start();
require_once '../config/db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Connexion/connexion.php');
    exit;
}

// Récupérer l'ID de l'utilisateur depuis l'URL
$id_utilisateur = $_GET['id_utilisateur'] ?? null;

// Vérifier que l'ID de l'utilisateur est valide
if (!$id_utilisateur || !is_numeric($id_utilisateur)) {
    die('Utilisateur introuvable.');
}

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("
    SELECT 
        nom, prenom, email, photo_profil, role, bio, date_creation, objectifs_fitness, objectif
    FROM utilisateurs
    WHERE id_utilisateur = ?
");
$stmt->execute([$id_utilisateur]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur existe
if (!$user) {
    die('Utilisateur introuvable.');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <main class="container mt-5">
        <section class="section">
            <div class="box">
                <h1 class="title is-4">Profil de <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></h1>
                <p><strong>Email :</strong> <?= htmlspecialchars($user['email']); ?></p>
                <p><strong>Badge :</strong> <?= htmlspecialchars($user['badge'] ?? 'Aucun badge attribué.'); ?></p>
                <p><strong>Rôle :</strong> <?= htmlspecialchars($user['role']); ?></p>
                <p><strong>Date de création :</strong> <?= htmlspecialchars($user['date_creation']); ?></p>
                <?php if ($user['photo_profil']): ?>
                    <img src="../../<?= htmlspecialchars($user['photo_profil']); ?>" alt="Photo de profil" class="profile-image" style="width: 150px; height: 150px; border-radius: 50%;">
                <?php else: ?>
                    <img src="../../uploads/profils/default.png" alt="Photo de profil par défaut" class="profile-image" style="width: 150px; height: 150px; border-radius: 50%;">
                <?php endif; ?>
                <p><strong>Bio :</strong> <?= nl2br(htmlspecialchars($user['bio'] ?? 'Aucune bio disponible.')); ?></p>
                <p><strong>Objectifs Fitness :</strong> <?= nl2br(htmlspecialchars($user['objectifs_fitness'] ?? 'Non renseigné.')); ?></p>
                <p><strong>Objectif principal :</strong> <?= htmlspecialchars($user['objectif'] ?? 'Non renseigné.'); ?></p>
            </div>
        </section>
        <a href="javascript:history.back()" class="button is-light">Retour</a>
    </main>
</body>
</html>