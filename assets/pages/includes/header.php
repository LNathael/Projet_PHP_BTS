<?php 
include '../includes/session_start.php';

////////////////////////////////////////////////////////////////////////
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT role FROM utilisateurs WHERE id_utilisateur = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $userRole = $stmt->fetchColumn();

    $isAdmin = $userRole === 'administrateur';
    $isSuperAdmin = $userRole === 'super_administrateur'; // Vérifiez que cette ligne est correcte
} else {
    $isAdmin = false;
    $isSuperAdmin = false;
}
////////////////////////////////
if (isset($_POST['supprimer_commande'])) {
    $id_commande = $_POST['id_commande'];

    // Vérifier si la commande est en attente
    $stmt = $pdo->prepare("SELECT statut_commande FROM commandes WHERE id_commande = ? AND id_utilisateur = ?");
    $stmt->execute([$id_commande, $user_id]);
    $commande = $stmt->fetch();

    if ($commande && $commande['statut_commande'] === 'en attente') {
        // Supprimer la commande
        $stmt = $pdo->prepare("DELETE FROM commandes WHERE id_commande = ?");
        $stmt->execute([$id_commande]);

        // Supprimer les détails de la commande
        $stmt = $pdo->prepare("DELETE FROM details_commande WHERE id_commande = ?");
        $stmt->execute([$id_commande]);

        $_SESSION['notification'] = "Commande supprimée avec succès.";
        header('Location: commandes.php');
        exit;
        } else {
        $_SESSION['notification'] = "Impossible de supprimer cette commande.";
        header('Location: commandes.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriStrong</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Styles principaux -->
    <link rel="stylesheet" href="../../css/custom.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bulma CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <!-- Favicon et Manifest -->
    <link rel="icon" href="../../img/favicon-16x16.png" type="image/png">
    <link rel="apple-touch-icon" href="../../img/favicon-32x32.png" sizes="32x32">
    <link rel="apple-touch-icon" href="../../img/favicon-192x192.png" sizes="192x192">
    <link rel="manifest" href="../../manifest.json">
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <!-- Scripts -->
    <script src="../../js/app.js" defer></script>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
                .then(function(registration) {
                    console.log('Service Worker registered with scope:', registration.scope);
                })
                .catch(function(error) {
                    console.log('Service Worker registration failed:', error);
                });
        }
    </script>
</head>
<body>
<header class="site-header">
    <nav class="navbar is-primary" role="navigation" aria-label="main navigation">
        <div class="container">
            <!-- Logo et menu burger -->
            <div class="navbar-brand">
                <a class="navbar-item" href="<?= isset($_SESSION['user_id']) ? '../Acceuil/accueil.php' : '../Acceuil/index.php'; ?>" style="padding: 0;">
                    <!-- Logo SVG -->
                    <img src="../../img/logo-150px.png" alt="NutriStrong Logo">
  
                </a>

                <!-- Menu burger pour mobile -->
                <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navMenu">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>
            
            <!-- Menu principal -->
            <div id="navMenu" class="navbar-menu">
                <div class="navbar-start">
                    <a href="../Acceuil/index.php" class="navbar-item">Accueil</a>
                    <a href="../Magasin/magasin.php" class="navbar-item">Magasin</a>
                    <a href="../recette/recettes.php" class="navbar-item">Recettes</a>
                    <a href="../blog/blog.php" class="navbar-item">Blog</a>

                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link">Programmes</a>
                        <div class="navbar-dropdown">
                        <a href="../Programme/programmes_personnalises.php" class="navbar-item is-primary">Programmes personnalisés</a>
                            <a href="../Programme/programmes_masse.php" class="navbar-item">Prise de masse</a>
                            <a href="../Programme/programmes_perte.php" class="navbar-item">Perte de poids</a>
                            <a href="../Programme/programmes_debutants.php" class="navbar-item">Débutants</a>
                        </div>
                    </div>
                </div>

                <!-- Barre de recherche visible uniquement sur les écrans larges -->
                

                <div class="navbar-end">
                    <div class="navbar-item is-hidden-touch">
                        <form action="../Recherche/recherche.php" method="GET">
                            <div class="field has-addons">
                                <div class="control">
                                    <input class="input" type="text" name="query" placeholder="Rechercher...">
                                </div>
                                <div class="control">
                                    <button class="button is-info" type="submit">
                                        <span class="icon">
                                            <i class="fas fa-search"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <a href="../Panier/panier.php" class="navbar-item">
                        Panier
                        <span class="tag is-danger is-rounded">
                            <?= isset($_SESSION['cart_quantity']) ? $_SESSION['cart_quantity'] : 0 ?>
                        </span>
                    </a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link" id="user-name"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></a>
                            <div class="navbar-dropdown">
                                <?php if ($isAdmin || $isSuperAdmin): ?>
                                    <a href="../admin/gestion_admin.php" class="navbar-item is-selected">Espace Admin</a>
                                    <hr class="navbar-divider">
                                <?php endif; ?>
                                <a href="../Connexion/compte.php" class="navbar-item ">Profil</a>
                                <a href="../Avis/avis.php" class="navbar-item">Avis</a>
                                <a href="../Connexion/deconnexion.php" class="navbar-item">Déconnexion</a>
                            </div>
                        </div>
                        
                    <?php else: ?>
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a href="../Acceuil/index.php" class="navbar-link">Compte</a>
                            <div class="navbar-dropdown">
                                <a href="../Connexion/inscription.php" class="navbar-item">S'inscrire</a>
                                <a href="../Connexion/connexion.php" class="navbar-item">Se Connecter</a>
                                <hr class="navbar-divider">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
        </div>
    </nav>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userNameElement = document.getElementById('user-name');
        const originalText = userNameElement ? userNameElement.textContent : '';

        if (userNameElement) {
            userNameElement.addEventListener('mouseover', function () {
                userNameElement.textContent = 'Mon Compte';
            });

            userNameElement.addEventListener('mouseout', function () {
                userNameElement.textContent = originalText;
            });
        }

        // Fermer les menus déroulants après un clic
        const dropdowns = document.querySelectorAll('.navbar-item.has-dropdown');
        dropdowns.forEach(dropdown => {
            dropdown.addEventListener('click', function () {
                dropdown.classList.remove('is-hoverable');
            });
        });
        const burger = document.querySelector('.navbar-burger');
        const menu = document.getElementById('navMenu');

        if (burger && menu) {
            burger.addEventListener('click', () => {
                burger.classList.toggle('is-active');
                menu.classList.toggle('is-active');
            });
        }
});

</script>
</body>
</html>