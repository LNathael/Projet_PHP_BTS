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
   <section class="section">
        <h2 class="title is-4">Avis des utilisateurs</h2>
        <?php
        // Récupérer les avis pour un programme spécifique
        try {
            $stmt = $pdo->prepare("SELECT * FROM avis WHERE id_programme IN (SELECT id_programme FROM programmes WHERE id_utilisateur = :id_utilisateur)");
            $stmt->execute(['id_utilisateur' => $userId]);
            $avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des avis : " . $e->getMessage());
        }
        ?>

        <?php if ($avis): ?>
            <?php foreach ($avis as $avis_item): ?>
                <div class="box">
                    <p><strong>Note :</strong> <?= htmlspecialchars($avis_item['note']) ?>/5</p>
                    <p><?= nl2br(htmlspecialchars($avis_item['commentaire'])) ?></p>
                    <p><small><em>Publié le <?= htmlspecialchars($avis_item['date_avis']) ?></em></small></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun avis pour vos programmes.</p>
        <?php endif; ?>
        <a href="../Avis/ajouter_avis.php" class="button is-link">Donner un avis</a>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>