<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php'; // Inclure la connexion à la base de données

// Récupération des produits depuis la base de données
$stmt = $pdo->query("SELECT * FROM produits");
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magasin</title>
    
    <!-- Polices Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles principaux -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="../../css/custom.css">
</head>
<body>
    <main class="container ">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="notification is-success">
                <?= htmlspecialchars($_SESSION['message']); ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        <!-- En-tête du magasin -->
        <section class="store-header has-text-centered">
            <h1 class="title is-2">Notre Magasin</h1>
            <p class="subtitle is-5">Découvrez nos meilleurs produits</p>

            <!-- Bouton pour accéder au panier -->
            <a href="../Panier/panier.php" class="button is-medium is-primary">
                <span class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </span>
                <span>Voir le panier</span>
            </a>
            <br></br>
        </section>

        <!-- Liste des produits -->
        <div class="columns is-multiline is-variable is-4">
        <?php if (!empty($produits)): ?>
            <?php foreach ($produits as $produit): ?>
                <div class="column is-one-quarter-desktop is-half-tablet">
                    <div class="card product-card <?= $produit['quantite_disponible'] <= 0 ? 'out-of-stock' : ''; ?>">
                        <!-- Image du produit -->
                        <div class="card-image">
                            <figure class="image is-4by3">
                                <a href="../Produit/detail_produit.php?id=<?= $produit['id_produit']; ?>">
                                    <img src="../../<?= htmlspecialchars($produit['image']); ?>" 
                                        alt="<?= htmlspecialchars($produit['nom_produit']); ?>">
                                </a>
                                
                            </figure>
                        </div>
                        <!-- Contenu du produit -->
                        <div class="card-content has-text-centered">
                            <p class="title is-5"><?= htmlspecialchars($produit['nom_produit']); ?></p>
                            <p class="subtitle is-6 has-text-weight-bold"><?= number_format($produit['prix'], 2, ',', ' '); ?> €</p>
                        </div>
                        <!-- Bouton ou message de rupture de stock -->
                        <footer class="card-footer">
                            <?php if ($produit['quantite_disponible'] > 0): ?>
                                <form method="POST" action="../Panier/panier.php" class="card-footer-item">
                                    <input type="hidden" name="id_produit" value="<?= $produit['id_produit']; ?>">
                                    <div class="field has-addons is-flex is-justify-content-center">
                                        <div class="control">
                                            <button type="submit" name="action" value="ajouter" class="button is-primary">
                                                Ajouter au panier
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </footer>
                    </div>
                    <?php if ($produit['quantite_disponible'] <= 0): ?>
                                    <!-- Bulle de rupture de stock -->
                                    <div class="notification is-danger has-text-centered is-overlay">
                                        Rupture de stock
                                    </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="column is-full">
                <p class="has-text-centered is-size-4">Aucun produit disponible pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>