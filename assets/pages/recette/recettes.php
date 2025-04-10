<?php
session_start();
include '../includes/header.php'; // Inclure le header
include '../config/db.php'; // Inclure la connexion à la base de données

$selectedCategory = $_GET['categorie'] ?? ''; // Récupérer la catégorie sélectionnée (ou vide par défaut)


// Récupérer les catégories disponibles
try {
    $stmt = $pdo->prepare("SELECT DISTINCT categorie FROM recettes ORDER BY categorie ASC");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des catégories : " . $e->getMessage());
}


// Récupérer les recettes selon la catégorie sélectionnée
try {
    // Si une catégorie est sélectionnée, filtrer les recettes par cette catégorie
    if (!empty($selectedCategory) && $selectedCategory !== 'toutes') {
        $stmt = $pdo->prepare("SELECT r.*, u.nom, u.prenom 
                               FROM recettes r
                               JOIN utilisateurs u ON r.id_utilisateur = u.id_utilisateur
                               WHERE r.categorie = :categorie
                               ORDER BY r.date_creation DESC");
        $stmt->execute([':categorie' => $selectedCategory]);
    } else {
        // Sinon, récupérer toutes les recettes
        $stmt = $pdo->prepare("SELECT r.*, u.nom, u.prenom 
                               FROM recettes r
                               JOIN utilisateurs u ON r.id_utilisateur = u.id_utilisateur
                               ORDER BY r.date_creation DESC");
        $stmt->execute();
    }
    $recettes = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur lors de la récupération des recettes : " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recettes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
<main class="container">
    <section class="section">
        <div class="is-flex is-justify-content-space-between is-align-items-center">
            <h1 class="title">Toutes les Recettes</h1>
            <!-- Filtre par catégorie -->
            <form method="GET" action="" class="select">
                <select name="categorie" onchange="this.form.submit()">
                    <option value="toutes" <?= $selectedCategory === 'toutes' ? 'selected' : ''; ?>>Toutes les catégories</option>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?= htmlspecialchars($categorie); ?>" <?= $selectedCategory === $categorie ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($categorie); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
            <a href="ajouter_recette.php" class="button is-primary">Ajouter une recette</a>
        </div>

        <?php if (!empty($recettes)): ?>
            <div class="columns is-multiline is-variable is-4">
                <?php foreach ($recettes as $recette): ?>
                    <div class="column is-one-quarter-desktop is-half-tablet">
                        <div class="card recipe-card">
                            <!-- Image de la recette -->
                            <div class="card-image">
                                <figure class="image is-4by3">
                                    <?php if (!empty($recette['image'])): ?>
                                        <img src="../../<?= htmlspecialchars($recette['image']); ?>" 
                                            alt="<?= htmlspecialchars($recette['titre']); ?>">
                                    <?php else: ?>
                                        <img src="../../images/default-recipe.jpg" 
                                            alt="Image par défaut">
                                    <?php endif; ?>
                                </figure>
                            </div>
                            <!-- Contenu de la recette -->
                            <div class="card-content">
                                <p class="title is-5"><?= htmlspecialchars($recette['titre']); ?></p>
                                <p class="subtitle is-6"><?= htmlspecialchars($recette['categorie']); ?></p>
                                <p><strong>Auteur :</strong> <?= htmlspecialchars($recette['prenom'] . ' ' . $recette['nom']); ?></p>
                                <p><strong>Date :</strong> <?= htmlspecialchars($recette['date_creation']); ?></p>
                                <p><?= htmlspecialchars(substr($recette['description'], 0, 100)) . '...'; ?></p>
                            </div>
                            <!-- Bouton pour voir la recette -->
                            <footer class="card-footer">
                                <a href="detail_recette.php?id=<?= $recette['id_recette']; ?>" class="card-footer-item button is-link">
                                    Voir la recette
                                </a>
                            </footer>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucune recette trouvée.</p>
        <?php endif; ?>
    </section>

</main>

<?php include '../includes/footer.php'; // Inclure le footer ?>
</body>
</html>
