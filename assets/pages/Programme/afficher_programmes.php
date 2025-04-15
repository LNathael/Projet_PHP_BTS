<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

include '../config/db.php';

$userId = $_SESSION['user_id'];
try {
    $stmt = $pdo->prepare("SELECT * FROM programmes WHERE id_utilisateur = :id_utilisateur ORDER BY created_at DESC");
    $stmt->execute(['id_utilisateur' => $userId]);
    $programmes = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur lors de la récupération des programmes : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <title>Vos Programmes</title>
</head>
<body>
<?php include '../includes/header.php'; ?>

<main class="container">
    <section class="section">
        <h1 class="title">Vos Programmes Personnalisés</h1>

        <?php if ($programmes): ?>
            <?php foreach ($programmes as $programme): ?>
                <div class="box">
                    <h2 class="title is-4"><?= htmlspecialchars($programme['objectif']); ?> - <?= htmlspecialchars($programme['niveau']); ?></h2>
                    <p><strong>Fréquence :</strong> <?= htmlspecialchars($programme['frequence']); ?> jours/semaine</p>
                    <p><strong>Programme :</strong></p>
                    <pre><?= htmlspecialchars($programme['programme']); ?></pre>
                    <!-- Bouton pour accéder au détail -->
                    <a href="../Programme/detail_programme.php?id=<?= $programme['id_programme']; ?>" class="button is-link">Voir le détail</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun programme enregistré.</p>
        <?php endif; ?>
    </section>
   <!-- Section des avis -->
   
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>