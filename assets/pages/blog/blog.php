<?php
require_once '../config/db.php'; // Connexion à la base de données
include '../includes/header.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    
    <!-- Polices Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles principaux -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<main class="container mt-5">
    <h1 class="title is-3">Blog</h1>
    <section class="content">
        <h2 class="title is-4">Derniers Articles</h2>
        <ul>
            <?php
            // Récupérer les articles depuis la base de données
            $stmt = $pdo->query("SELECT id_article, titre, contenu, date_creation, auteur FROM articles ORDER BY date_creation DESC");
            $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier s'il y a des articles
            if ($articles):
                foreach ($articles as $article): ?>
                    <li class="box mb-4">
                        <a href="detail_article.php?id=<?= htmlspecialchars($article['id_article']); ?>" class="title is-5">
                            <?= htmlspecialchars($article['titre']); ?>
                        </a>
                        <p class="is-size-6 has-text-grey">
                            <strong>Auteur :</strong> <?= htmlspecialchars($article['auteur'] ?? 'Anonyme'); ?> |
                            <strong>Date :</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($article['date_creation']))); ?>
                        </p>
                        <p><?= htmlspecialchars(substr($article['contenu'], 0, 150)) . '...'; ?></p>
                        <a href="detail_article.php?id=<?= htmlspecialchars($article['id_article']); ?>" class="button is-link is-small mt-2">Lire la suite</a>
                    </li>
                <?php endforeach;
            else: ?>
                <p>Aucun article disponible pour le moment.</p>
            <?php endif; ?>
        </ul>
    </section>
</main>

<?php include '../includes/footer.php'; ?>