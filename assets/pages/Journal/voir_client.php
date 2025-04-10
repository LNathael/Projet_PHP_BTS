<?php
session_start();
require_once '../config/db.php'; // Inclure la connexion à la base de données

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrateur') {
    header('Location: ../Connexion/connexion.php');
    exit;
}

// Récupérer l'ID du client
$id_client = $_GET['id'] ?? null;

if (!$id_client) {
    die('Client introuvable.');
}

// Récupérer les informations du client
$stmt = $pdo->prepare("
    SELECT nom, prenom, email, date_naissance, sexe, date_creation, bio, objectifs_fitness 
    FROM utilisateurs 
    WHERE id_utilisateur = :id_client
");
$stmt->execute(['id_client' => $id_client]);
$client = $stmt->fetch();

if (!$client) {
    die('Client introuvable.');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voir Client</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <section class="section">
        <div class="container">
            <h1 class="title">Informations du Client</h1>
            <div class="box">
                <p><strong>Nom :</strong> <?= htmlspecialchars($client['nom']); ?></p>
                <p><strong>Prénom :</strong> <?= htmlspecialchars($client['prenom']); ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($client['email']); ?></p>
                <p><strong>Date de naissance :</strong> <?= htmlspecialchars($client['date_naissance'] ?? 'Non renseignée'); ?></p>
                <p><strong>Sexe :</strong> <?= htmlspecialchars($client['sexe'] ?? 'Non renseigné'); ?></p>
                <p><strong>Date d'inscription :</strong> <?= htmlspecialchars($client['date_creation']); ?></p>
                <p><strong>Bio :</strong> <?= nl2br(htmlspecialchars($client['bio'] ?? 'Non renseignée')); ?></p>
                <p><strong>Objectifs fitness :</strong> <?= nl2br(htmlspecialchars($client['objectifs_fitness'] ?? 'Non renseignés')); ?></p>
            </div>
            <a href="journal_entrainement.php" class="button is-light">Retour à journal d'entrainements</a>
        </div>
    </section>
    <?php include '../includes/footer.php'; ?>
</body>
</html>