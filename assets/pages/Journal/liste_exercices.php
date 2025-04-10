<?php
session_start();
require_once '../config/db.php'; // Inclure la connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Connexion/connexion.php');
    exit;
}
// Filtrer les exercices par catégorie si une catégorie est sélectionnée
$categorie_selectionnee = $_GET['categorie'] ?? null;
if ($categorie_selectionnee) {
    $stmt = $pdo->prepare("SELECT * FROM exercices WHERE categorie = :categorie");
    $stmt->execute(['categorie' => $categorie_selectionnee]);
    $exercices = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $exercices = $pdo->query("SELECT * FROM exercices")->fetchAll(PDO::FETCH_ASSOC);
}
// Récupérer tous les exercices
$exercices = $pdo->query("SELECT * FROM exercices")->fetchAll(PDO::FETCH_ASSOC);
// Récupérer les catégories existantes
$categories = $pdo->query("SELECT DISTINCT categorie FROM exercices WHERE categorie IS NOT NULL")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer l'exercice sélectionné
$selected_exercice_id = $_GET['id_exercice'] ?? null;
$selected_exercice = null;

if ($selected_exercice_id) {
    $stmt = $pdo->prepare("SELECT * FROM exercices WHERE id_exercice = :id_exercice");
    $stmt->execute(['id_exercice' => $selected_exercice_id]);
    $selected_exercice = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Exercices</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <section class="section">
        <div class="container">
             <!-- Bouton retour -->
             <a href="journal_entrainement.php" class="button is-light mb-4">
                <span class="icon">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span>Retour au journal d'entraînement</span>
            </a>
            <h1 class="title">Liste des Exercices</h1>

            <!-- Menu déroulant -->
            <form method="GET" action="">
            <div class="field is-grouped">
                <!-- Menu déroulant pour les muscles -->
                <div class="control">
                    <div class="select">
                        <select id="categorie" name="categorie" onchange="filterExercises()">
                            <option value="">-- Sélectionnez un muscle --</option>
                            <?php foreach ($categories as $categorie): ?>
                                <option value="<?= htmlspecialchars($categorie['categorie']); ?>" <?= ($_GET['categorie'] ?? '') === $categorie['categorie'] ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($categorie['categorie']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Menu déroulant pour les exercices -->
                <div class="control">
                    <div class="select">
                        <select id="id_exercice" name="id_exercice">
                            <option value="">-- Sélectionnez un exercice --</option>
                            <?php foreach ($exercices as $exercice): ?>
                                <option value="<?= $exercice['id_exercice']; ?>" data-categorie="<?= htmlspecialchars($exercice['categorie']); ?>" <?= ($_GET['id_exercice'] ?? '') == $exercice['id_exercice'] ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($exercice['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Bouton pour soumettre -->
                <div class="control">
                    <button class="button is-primary" type="submit">Filtrer</button>
                </div>
            </div>
        </form>

            <!-- Affichage des informations de l'exercice sélectionné -->
            <?php if ($selected_exercice): ?>
                <div class="box">
                    <h2 class="title is-4"><?= htmlspecialchars($selected_exercice['nom']); ?></h2>
                    <p><?= nl2br(htmlspecialchars($selected_exercice['description'])); ?></p>

                    <?php if (!empty($selected_exercice['video_path'])): ?>
                        <video controls width="100%">
                            <source src="<?= htmlspecialchars($selected_exercice['video_path']); ?>" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture de vidéos.
                        </video>
                    <?php elseif (!empty($selected_exercice['image_path'])): ?>
                        <img src="<?= htmlspecialchars($selected_exercice['image_path']); ?>" alt="Image de l'exercice" style="max-width: 100%; height: auto;">
                    <?php endif; ?>
                    <a href="modifier_exercice.php?id=<?= $selected_exercice['id_exercice']; ?>" class="button is-warning is-small mt-2">Modifier</a>
                    <a href="supprimer_exercice.php?id=<?= $selected_exercice['id_exercice']; ?>" class="button is-danger is-small mt-2">Supprimer</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php include '../includes/footer.php'; ?>
</body>
<script>
    function filterExercises() {
        const selectedCategory = document.getElementById('categorie').value;
        const exerciceOptions = document.querySelectorAll('#id_exercice option');

        exerciceOptions.forEach(option => {
            if (!option.dataset.categorie || option.dataset.categorie === selectedCategory || selectedCategory === '') {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });

        // Réinitialiser la sélection si l'option sélectionnée n'est plus visible
        const selectedExercice = document.getElementById('id_exercice');
        if (selectedExercice.options[selectedExercice.selectedIndex].style.display === 'none') {
            selectedExercice.value = '';
        }
    }

    // Appeler la fonction au chargement de la page pour appliquer le filtre initial
    document.addEventListener('DOMContentLoaded', filterExercises);
</script>
</html>