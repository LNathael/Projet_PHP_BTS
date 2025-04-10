<?php
session_start();
require_once '../config/db.php'; // Inclure la connexion à la base de données

if (!isset($_SESSION['user_id'])) {
    header('Location: ../Connexion/connexion.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer l'ID de la séance
$id_entrainement = $_GET['id'] ?? null;

if (!$id_entrainement) {
    die('Séance introuvable.');
}

// Récupérer les informations générales de la séance
$stmt = $pdo->prepare("
    SELECT titre, date, description 
    FROM entrainements 
    WHERE id_entrainement = :id_entrainement AND id_utilisateur = :id_utilisateur
");
$stmt->execute([
    'id_entrainement' => $id_entrainement,
    'id_utilisateur' => $user_id
]);
$seance = $stmt->fetch();

if (!$seance) {
    die('Séance introuvable ou vous n\'avez pas accès à cette séance.');
}

// Récupérer les exercices associés à la séance
$stmt = $pdo->prepare("
    SELECT e.nom AS exercice, se.poids, se.repetitions, se.series, se.ressenti 
    FROM seance_exercice se
    JOIN exercices e ON se.id_exercice = e.id_exercice
    WHERE se.id_entrainement = :id_entrainement
");
$stmt->execute(['id_entrainement' => $id_entrainement]);
$exercices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($seance['titre']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <section class="section">
        <div class="container">
            <h1 class="title">Détails de la Séance</h1>

            <!-- Informations générales -->
            <div class="box">
                <h2 class="title is-4"><?= htmlspecialchars($seance['titre']); ?></h2>
                <p><strong>Date :</strong> <?= htmlspecialchars($seance['date']); ?></p>
                <p><strong>Description :</strong> <?= htmlspecialchars($seance['description'] ?? 'Aucune description.'); ?></p>
            </div>

            <!-- Exercices de la séance -->
            <h2 class="title is-5">Exercices</h2>
            <?php if ($exercices): ?>
                <table class="table is-striped is-fullwidth">
                    <thead>
                        <tr>
                            <th>Exercice</th>
                            <th>Poids (kg)</th>
                            <th>Répétitions</th>
                            <th>Séries</th>
                            <th>Ressenti</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($exercices as $exercice): ?>
                            <tr>
                                <td><?= htmlspecialchars($exercice['exercice']); ?></td>
                                <td><?= htmlspecialchars($exercice['poids']); ?></td>
                                <td><?= htmlspecialchars($exercice['repetitions']); ?></td>
                                <td><?= htmlspecialchars($exercice['series']); ?></td>
                                <td><?= htmlspecialchars($exercice['ressenti'] ?? 'N/A'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucun exercice enregistré pour cette séance.</p>
            <?php endif; ?>

            <!-- Bouton retour -->
            <a href="seances_effectuees.php" class="button is-light mt-4">Retour aux séances</a>
        </div>
    </section>
    <?php include '../includes/footer.php'; ?>
</body>
</html>