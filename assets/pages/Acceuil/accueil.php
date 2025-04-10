<?php
session_start();
require_once '../config/db.php'; // Connexion à la base de données
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
$user_id = $_SESSION['user_id'];
$isAdmin = $_SESSION['role'] === 'administrateur';
$isSuperAdmin = $_SESSION['role'] === 'super_administrateur';

$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();
$userName = $user ? htmlspecialchars($user['nom']) : 'Utilisateur';

// Récupération sécurisée des produits et recettes
$produits = $pdo->query("SELECT * FROM produits LIMIT 15")->fetchAll(PDO::FETCH_ASSOC);
$recettes = $pdo->query("SELECT * FROM recettes LIMIT 15")->fetchAll(PDO::FETCH_ASSOC);
// Trier les produits par nombre de vues (du plus vu au moins vu)
$produits = $pdo->query("SELECT * FROM produits ORDER BY vues DESC LIMIT 15")->fetchAll(PDO::FETCH_ASSOC);

// Trier les recettes par nombre de vues (du plus vu au moins vu)
$recettes = $pdo->query("SELECT * FROM recettes ORDER BY vues DESC LIMIT 15")->fetchAll(PDO::FETCH_ASSOC);

$videos = ['video_1.mp4', 'video_2.mp4', 'video_3.mp4','video_5.mp4','video_6.mp4','video_7.mp4','video_8.mp4'];
$selected_video = $videos[array_rand($videos)];

?>
<?php include '../includes/popup_last_message.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?? '' ?>">
    
    <!-- Polices et Styles -->
    <link rel="stylesheet" href="../../css/custom.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script src="../../js/app.js" defer></script>
    
</head> 
<body>
<main class="container mt-5">
    <!-- Section principale -->
    <section class="hero is-primary is-fullheight-with-navbar has-text-centered" style="position: relative; overflow: hidden;">
        <!-- Vidéo en arrière-plan -->
        <video id="background-video" class="hero-video" autoplay muted loop >
            <source src="../../videos/<?= htmlspecialchars($selected_video) ?>" type="video/mp4">
            Votre navigateur ne supporte pas la lecture de vidéos.
        </video>
        <!-- Contenu principal -->
        <div class="hero-body" style="position: relative; z-index: 1;">
            <div class="container">
            <h1 class="title is-size-1 has-text-white text-outline" style="font-size: 4rem; font-weight: bold;">
                Bienvenue sur NutriStrong, <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?> !
            </h1>
            <p class="subtitle is-size-3 has-text-white text-outline" style="font-size: 1.5rem;">
                Votre plateforme de fitness et de nutrition.
            </p>

                <a href="#sous-sections" class="button is-primary is-large is-rounded">Découvrir NutriStrong</a>
            </div>
        </div>
    </section>

    <script>
        // Tableau des vidéos
        const videos = <?= json_encode($videos); ?>;
        const videoElement = document.getElementById('background-video');
        let currentVideoIndex = videos.indexOf("<?= $selected_video; ?>");

        // Fonction pour changer de vidéo
        function changeVideo() {
            currentVideoIndex = (currentVideoIndex + 1) % videos.length; // Passer à la vidéo suivante
            const newVideoSrc = "../../videos/" + videos[currentVideoIndex];
            videoElement.querySelector('source').src = newVideoSrc;
            videoElement.load(); // Recharger la vidéo
            videoElement.play(); // Lire la nouvelle vidéo
        }

        // Écouter la fin de la vidéo pour changer automatiquement
        videoElement.addEventListener('ended', changeVideo);
    </script>

    <!-- Sous-sections -->
    <section id="sous-sections" class="section">
        <div class="container">
            <!-- Sous-section 1 : Liens principaux -->
            <section class="hero is-light has-text-centered">
                <div class="hero-body">
                    <h2 class="title is-4">Explorez nos fonctionnalités</h2>
                    <div class="buttons is-centered">
                        <a href="../Calorie/calculateur_calories.php" class="button is-primary">Calculateur de calories</a>
                        
                        <a href="../Salon/salons.php" class="button is-info">💬 Chat Communautaire</a>
                        <a href="../Journal/journal_entrainement.php" class="button is-success">Journal d'Entraînement</a>
                    </div>
                </div>
            </section>

            <!-- Sous-section 2 : Produits -->
            <section class="section products-section">
                <h2 class="title is-4 has-text-centered">Nos meilleurs produits</h2>
                <div class="swiper produits-swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($produits as $produit): ?>
                            <div class="swiper-slide">
                                <div class="card">
                                    <div class="card-image">
                                        <figure class="image is-4by3">
                                            <img src="../../<?= htmlspecialchars($produit['image']); ?>" alt="<?= htmlspecialchars($produit['nom_produit']); ?>">
                                        </figure>
                                    </div>
                                    <div class="card-content">
                                        <p class="title is-5"><?= htmlspecialchars($produit['nom_produit']); ?></p>
                                        <p class="subtitle is-6"><?= htmlspecialchars(substr($produit['description'], 0, 50)) . '...'; ?></p>
                                    </div>
                                    <footer class="card-footer">
                                        <a href="../Produit/detail_produit.php?id=<?= $produit['id_produit']; ?>" class="card-footer-item">Voir le produit</a>
                                    </footer>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Pagination et navigation -->
                    <div class="swiper-pagination produits-pagination"></div>
                    <div class="swiper-button-next produits-button-next"></div>
                    <div class="swiper-button-prev produits-button-prev"></div>
                </div>
            </section>
            <!-- Sous-section 3 : Recettes -->
            <section class="section recipes-section">
                <h2 class="title is-4 has-text-centered">Nos meilleures recettes</h2>
                <div class="swiper recettes-swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($recettes as $recette): ?>
                            <div class="swiper-slide">
                                <div class="card">
                                    <div class="card-image">
                                        <figure class="image is-4by3">
                                            <img src="../../<?= htmlspecialchars($recette['image']); ?>" alt="<?= htmlspecialchars($recette['titre']); ?>">
                                        </figure>
                                    </div>
                                    <div class="card-content">
                                        <p class="title is-5"><?= htmlspecialchars($recette['titre']); ?></p>
                                        <p class="subtitle is-6"><?= htmlspecialchars(substr($recette['description'], 0, 20)) . '...'; ?></p>
                                    </div>
                                    <footer class="card-footer">
                                        <a href="../recette/detail_recette.php?id=<?= $recette['id_recette']; ?>" class="card-footer-item">Voir la recette</a>
                                    </footer>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Pagination et navigation -->
                    <div class="swiper-pagination recettes-pagination"></div>
                    <div class="swiper-button-next recettes-button-next"></div>
                    <div class="swiper-button-prev recettes-button-prev"></div>
                </div>
            </section>
        </div>
    </section>
</main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
