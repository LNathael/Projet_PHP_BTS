<?php
session_start();
include '../includes/header.php'; // Inclure le header
include '../config/db.php'; // Inclure la connexion à la base de données

// Récupérer l'ID de la recette
$id_recette = $_GET['id'] ?? null;

if (!$id_recette) {
    die('Recette introuvable.');
}

// Incrémenter le compteur de vues pour la recette
$stmt_update = $pdo->prepare("UPDATE recettes SET vues = vues + 1 WHERE id_recette = ?");
$stmt_update->execute([$id_recette]);

// Récupérer les détails de la recette
try {
    $stmt = $pdo->prepare("SELECT r.*, u.nom, u.prenom 
                           FROM recettes r
                           JOIN utilisateurs u ON r.id_utilisateur = u.id_utilisateur
                           WHERE r.id_recette = ?");
    $stmt->execute([$id_recette]);
    $recette = $stmt->fetch();

    if (!$recette) {
        die('Recette introuvable.');
    }

    // Récupérer les avis pour une recette spécifique
    $stmt_avis = $pdo->prepare("
        SELECT a.*, u.nom, u.prenom 
        FROM avis a
        JOIN utilisateurs u ON a.id_utilisateur = u.id_utilisateur
        WHERE a.type_contenu = 'recette' AND a.contenu_id = :id
        ORDER BY a.date_avis DESC
    ");
    $stmt_avis->execute(['id' => $id_recette]);
    $avis = $stmt_avis->fetchAll(PDO::FETCH_ASSOC);

    // Calculer la moyenne des avis
    $stmt_moyenne = $pdo->prepare("
        SELECT AVG(note) as moyenne 
        FROM avis 
        WHERE type_contenu = 'recette' AND contenu_id = :id
    ");
    $stmt_moyenne->execute(['id' => $id_recette]);
    $moyenne = $stmt_moyenne->fetchColumn();

} catch (PDOException $e) {
    die("Erreur lors de la récupération de la recette : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($recette['titre']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        function toggleAvis() {
            var avisElements = document.querySelectorAll('.avis-item');
            for (var i = 2; i < avisElements.length; i++) {
                avisElements[i].classList.toggle('is-hidden');
            }
            var button = document.getElementById('toggleAvisButton');
            var icon = button.querySelector('i');
            if (icon.classList.contains('fa-chevron-down')) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }
    </script>
</head>
<body>
<main class="container mt-5">
    <section class="section">
        <h1 class="title has-text-centered"><?= htmlspecialchars($recette['titre']); ?></h1>
        <div class="columns is-centered">
        <div class="column is-6">
                <figure class="image is-4by3" style="overflow: hidden; border-radius: 8px;">
                    <?php if (!empty($recette['image'])): ?>
                        <img src="../../<?= htmlspecialchars($recette['image']); ?>" 
                            alt="<?= htmlspecialchars($recette['titre']); ?>" 
                            style="object-fit: cover; width: 100%; height: 100%;">
                    <?php else: ?>
                        <img src="../../uploads/default_recipe.png" 
                            alt="Image par défaut" 
                            style="object-fit: cover; width: 100%; height: 100%;">
                    <?php endif; ?>
                </figure>
            </div>
            <div class="column is-6">
                <div class="content">
                    <p><strong>Catégorie :</strong> <?= htmlspecialchars($recette['categorie']); ?></p>
                    <p><strong>Auteur :</strong> <?= htmlspecialchars($recette['prenom'] . ' ' . $recette['nom']); ?></p>
                    <p><strong>Date :</strong> <?= htmlspecialchars($recette['date_creation']); ?></p>
                    <hr>
                    <h2 class="title is-5">💪 Description</h2>
                    <p><?= nl2br(htmlspecialchars($recette['description'])); ?></p>
                    <hr>
                </div>
            </div>
        </div>
        </div>
        <hr>
        <div class="columns">
            <div class="column">
                <h2 class="title is-5">📝 Ingrédients</h2>
                <p><?= nl2br(htmlspecialchars($recette['ingredients'])); ?></p>
                
                <hr> <!-- Added a horizontal line for separation -->
                <h2 class="title is-5">🌀 Préparation</h2>
                <p><?= nl2br(htmlspecialchars($recette['etapes'])); ?></p>
            </div>
        </div>
    </section>

    <section class="section">
        <h2 class="title is-4">Avis des utilisateurs</h2>
        <p><strong>Moyenne des avis :</strong> <?= $moyenne ? number_format($moyenne, 2) : 'Aucun avis'; ?>/5</p>
        <?php if ($avis): ?>
            <div class="columns is-multiline">
                <?php foreach ($avis as $index => $avis_item): ?>
                    <div class="column is-half">
                        <div class="box">
                            <p><strong>Utilisateur :</strong> <?= htmlspecialchars($avis_item['prenom'] . ' ' . $avis_item['nom']) ?></p>
                            <p><strong>Note :</strong> <?= htmlspecialchars($avis_item['note']) ?>/5</p>
                            <p><?= nl2br(htmlspecialchars($avis_item['commentaire'])) ?></p>
                            <p><small><em>Publié le <?= htmlspecialchars($avis_item['date_avis']) ?></em></small></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucun avis pour cette recette.</p>
        <?php endif; ?>
        <a href="../Avis/avis.php?type_contenu=recette&contenu_id=<?= $id_recette; ?>" class="button is-link mt-4">Laisser un avis</a>
    </section>
</main>





<?php include '../includes/footer.php'; ?>
</body>
</html>