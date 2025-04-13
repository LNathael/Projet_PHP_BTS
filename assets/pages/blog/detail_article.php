<?php
require_once '../config/db.php'; // Connexion à la base de données
include '../includes/header.php';

// Récupérer l'ID de l'article depuis l'URL
$id_article = $_GET['id'] ?? null;

// Vérifier que l'ID est valide
if (!$id_article || !is_numeric($id_article)) {
    die('Article introuvable.');
}

// Récupérer les informations de l'article
$stmt = $pdo->prepare("SELECT titre, contenu, date_creation, auteur FROM articles WHERE id_article = ?");
$stmt->execute([$id_article]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'article existe
if (!$article) {
    die('Article introuvable.');
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['titre']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>

<main class="container mt-5">
    <h1 class="title is-3"><?= htmlspecialchars($article['titre']); ?></h1>
    <p class="is-size-6 has-text-grey">
        <strong>Auteur :</strong> <?= htmlspecialchars($article['auteur'] ?? 'Anonyme'); ?> |
        <strong>Date :</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($article['date_creation']))); ?>
    </p>
    <section class="content">
        <p><?= nl2br(htmlspecialchars($article['contenu'])); ?></p>
    </section>
    <a href="../blog/blog.php" class="button is-light mt-4">Retour au blog</a>
</main>

<?php include '../includes/footer.php'; ?>