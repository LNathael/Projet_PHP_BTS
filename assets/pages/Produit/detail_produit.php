<?php
session_start();
include '../includes/header.php'; // Inclure le header
include '../config/db.php'; // Inclure la connexion à la base de données




// Récupérer l'ID du produit
$id_produit = $_GET['id'] ?? null;

if (!$id_produit) {
    die('Produit introuvable.');
}

// Incrémenter le compteur de vues pour le produit
$stmt_update = $pdo->prepare("UPDATE produits SET vues = vues + 1 WHERE id_produit = ?");
$stmt_update->execute([$id_produit]);

// Récupérer les détails du produit
try {
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE id_produit = ?");
    $stmt->execute([$id_produit]);
    $produit = $stmt->fetch();

    if (!$produit) {
        die('Produit introuvable.');
    }

    // Récupérer les avis pour un produit spécifique
    $stmt_avis = $pdo->prepare("
        SELECT a.*, u.nom, u.prenom 
        FROM avis a
        JOIN utilisateurs u ON a.id_utilisateur = u.id_utilisateur
        WHERE a.type_contenu = 'produit' AND a.contenu_id = :id
        ORDER BY a.date_avis DESC
    ");
    $stmt_avis->execute(['id' => $id_produit]);
    $avis = $stmt_avis->fetchAll(PDO::FETCH_ASSOC);

    // Calculer la moyenne des avis
    $stmt_moyenne = $pdo->prepare("
        SELECT AVG(note) as moyenne 
        FROM avis 
        WHERE type_contenu = 'produit' AND contenu_id = :id
    ");
    $stmt_moyenne->execute(['id' => $id_produit]);
    $moyenne = $stmt_moyenne->fetchColumn();

} catch (PDOException $e) {
    die("Erreur lors de la récupération du produit : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produit['nom_produit']); ?></title>
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
        <div class="columns is-vcentered">
            <div class="column is-half">
                <figure class="image is-square">
                    <?php if (!empty($produit['image'])): ?>
                        <img src="../../<?= htmlspecialchars($produit['image']); ?>" 
                             alt="<?= htmlspecialchars($produit['nom_produit']); ?>" 
                             style="border-radius: 8px;">
                    <?php else: ?>
                        <img src="../../uploads/default_product.png" alt="Image par défaut" style="border-radius: 8px;">
                    <?php endif; ?>
                </figure>
            </div>
            <div class="column is-half">
                <h1 class="title"><?= htmlspecialchars($produit['nom_produit']); ?></h1>
                <p class="subtitle"><strong>Libellé :</strong> <?= htmlspecialchars($produit['libelle'] ?? 'Non disponible'); ?></p>
                <p><strong>Prix :</strong> <span class="has-text-primary"><?= number_format($produit['prix'], 2, ',', ' '); ?> €</span></p>
                <p><strong>Quantité disponible :</strong> <?= htmlspecialchars($produit['quantite_disponible']); ?></p>
                <hr>
                <h2 class="title is-5">Description</h2>
                <p><?= nl2br(htmlspecialchars($produit['description'])); ?></p>
                <hr>
                <?php if ($produit['quantite_disponible'] > 0): ?>
                    <form method="POST" action="../Panier/panier.php">
                        <input type="hidden" name="id_produit" value="<?= $produit['id_produit']; ?>">
                        <div class="field has-addons">
                            <div class="control">
                                <input class="input" type="number" name="quantite" value="1" min="1" max="<?= $produit['quantite_disponible']; ?>">
                            </div>
                            <div class="control">
                                <button type="submit" name="action" value="ajouter" class="button is-primary">
                                    Ajouter au panier
                                </button>
                            </div>
                        </div>
                    </form>
                <?php else: ?>
                    <p class="has-text-danger">Ce produit est actuellement en rupture de stock.</p>
                <?php endif; ?>
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
            <p>Aucun avis pour ce produit.</p>
        <?php endif; ?>
        <a href="../Avis/avis.php?type_contenu=produit&contenu_id=<?= $id_produit; ?>" class="button is-link mt-4">Laisser un avis</a>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>