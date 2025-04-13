<?php
include '../includes/session_start.php';

// Rediriger les utilisateurs connectés vers accueil.php
if (isset($_SESSION['user_id'])) {
    header('Location: accueil.php');
    exit;
}

// Sélectionner une vidéo aléatoire
$videos = ['video_1.mp4', 'video_2.mp4'];
$selected_video = $videos[array_rand($videos)];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - NutriStrong</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <link rel="shortcut icon" type="image/png" href="img/logo.png" />
    
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <section class="hero is-primary is-fullheight-with-navbar">
        <video class="hero-video" autoplay muted loop>
            <source src="../../videos/<?= htmlspecialchars($selected_video) ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="hero-body">
            <div class="container has-text-centered">
                <h1 class="title has-text-white">
                    Transforme ton corps, booste ta santé !
                </h1>
                <h2 class="subtitle has-text-white">
                    Rejoins-nous et commence ton voyage vers une meilleure santé et forme physique.
                </h2>
                <div class="buttons is-centered">
                    <a href="../Connexion/connexion.php" class="button is-medium is-primary is-light is-rounded">Se connecter</a>
                </div>
                <div class="buttons is-centered">
                    <a href="../Connexion/inscription.php" class="button is-medium is-info is-dark is-rounded">Inscription rapide</a>
                </div>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
