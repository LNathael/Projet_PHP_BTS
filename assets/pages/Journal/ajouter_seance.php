<?php
session_start();
require_once '../config/db.php'; // Inclure la connexion à la base de données

if (!isset($_SESSION['user_id'])) {
    header('Location: ../Connexion/connexion.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$erreurs = [];

// Récupérer les exercices disponibles
$exercices = $pdo->query("SELECT * FROM exercices")->fetchAll(PDO::FETCH_ASSOC);
// Récupérer les catégories distinctes
$categories = $pdo->query("SELECT DISTINCT categorie FROM exercices WHERE categorie IS NOT NULL")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Utiliser la date fournie par le formulaire ou la date du jour par défaut
    $date = trim($_POST['date'] ?? date('Y-m-d'));
    $exercice_ids = $_POST['exercice_ids'] ?? [];
    $poids = $_POST['poids'] ?? [];
    $repetitions = $_POST['repetitions'] ?? [];
    $series = $_POST['series'] ?? [];
    $ressenti = $_POST['ressenti'] ?? [];
    $titre = trim($_POST['titre'] ?? '');

    // Validation des champs
    if (empty($titre)) {
        $erreurs[] = "Le titre de la séance est obligatoire.";
    }
    // Validation des champs obligatoires
    if (empty($date) || empty($exercice_ids) || empty($poids) || empty($repetitions) || empty($series)) {
        $erreurs[] = "Tous les champs obligatoires doivent être remplis.";
    }
    $stmt = $pdo->prepare("INSERT INTO entrainements (id_utilisateur, date) VALUES (:id_utilisateur, :date)");
    $stmt->execute([
    ':id_utilisateur' => $user_id,
    ':date' => $date
]);
    if (empty($erreurs)) {
        try {
            $pdo->beginTransaction();

            // Insérer la séance dans la table `entrainements`
            $stmt = $pdo->prepare("INSERT INTO entrainements (id_utilisateur, titre, date) VALUES (:id_utilisateur, :titre, :date)");
            $stmt->execute([
                ':id_utilisateur' => $user_id,
                ':titre' => $titre,
                ':date' => $date
            ]);

            $id_entrainement = $pdo->lastInsertId();
            // Insérer les séries dans la table `series`
            $current_date = date('Y-m-d'); // Date du jour
            
            foreach ($exercice_ids as $index => $id_exercice) {
                $stmt = $pdo->prepare("INSERT INTO seance_exercice (id_entrainement, id_exercice, poids, repetitions, series, ressenti, date) VALUES (:id_entrainement, :id_exercice, :poids, :repetitions, :series, :ressenti, :date)");
                $stmt->execute([
                    ':id_entrainement' => $id_entrainement,
                    ':id_exercice' => $id_exercice,
                    ':poids' => $poids[$index],
                    ':repetitions' => $repetitions[$index],
                    ':series' => $series[$index],
                    ':ressenti' => $ressenti[$index] ?? '',
                    ':date' => $current_date
                ]);
            }

            $pdo->commit();
            header('Location: journal_entrainement.php');
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            $erreurs[] = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Séance</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <section class="section">
        <div class="container">
            <h1 class="title">Ajouter une Séance</h1>

            <!-- Affichage des erreurs -->
            <?php if (!empty($erreurs)): ?>
                <div class="notification is-danger">
                    <?php foreach ($erreurs as $erreur): ?>
                        <p><?= htmlspecialchars($erreur); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Formulaire d'ajout de séance -->
            <form action="" method="POST" id="seance-form">
                <div class="field">
                    <label class="label">Date</label>
                    <div class="control">
                        <input class="input" type="date" name="date" value="<?= htmlspecialchars($date ?? date('Y-m-d')); ?>" required>
                    </div>
                </div>
                <div class="field">
                            <label class="label">Titre de la séance</label>
                            <div class="control">
                                <input class="input" type="text" name="titre" value="<?= htmlspecialchars($titre ?? ''); ?>" required>
                            </div>
                </div>       
                <div id="exercises-container">

                    <div class="box exercise-box">
                        
                        <div class="field">
                            <label class="label">Exercice</label>
                            <div class="control">
                                <div class="select">
                                <select name="exercice_ids[]" class="exercice-select">
                                    <?php foreach ($exercices as $exercice): ?>
                                        <option value="<?= $exercice['id_exercice']; ?>" data-categorie="<?= htmlspecialchars($exercice['categorie']); ?>">
                                            <?= htmlspecialchars($exercice['nom']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Catégorie</label>
                                <div class="control">
                                    <div class="select">
                                        <select id="categorie" name="categorie" onchange="filterExercises()">
                                            <option value="">-- Sélectionnez une catégorie --</option>
                                            <?php foreach ($categories as $categorie): ?>
                                                <option value="<?= htmlspecialchars($categorie['categorie']); ?>">
                                                    <?= htmlspecialchars($categorie['categorie']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Poids (kg)</label>
                            <div class="control">
                                <input class="input" type="number" step="0.01" name="poids[]" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Répétitions</label>
                            <div class="control">
                                <input class="input" type="number" name="repetitions[]" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Nombre de séries</label>
                            <div class="control">
                                <input class="input" type="number" name="series[]" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Ressenti</label>
                            <div class="control">
                                <input class="input" type="text" name="ressenti[]">
                            </div>
                        </div>
                        <button type="button" class="button is-danger remove-exercise">Supprimer</button>
                    </div>
                </div>
                <br></br>
                <div class="control">
                    <button type="button" class="button is-info" id="add-exercise">Ajouter un exercice</button>
                </div>
                <br></br>                                       
                <div class="control">
                    <button class="button is-link is-fullwidth" type="submit">Enregistrer</button>
                </div>
            </form>
        </div>
    </section>
    <?php include '../includes/footer.php'; ?>
    <script>
        function filterExercises() {
            const selectedCategory = document.getElementById('categorie').value;
            const exerciceSelects = document.querySelectorAll('.exercice-select');

            exerciceSelects.forEach(select => {
                const options = select.querySelectorAll('option');
                options.forEach(option => {
                    if (!option.dataset.categorie || option.dataset.categorie === selectedCategory || selectedCategory === '') {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                });

                // Réinitialiser la sélection si l'option sélectionnée n'est plus visible
                if (select.options[select.selectedIndex].style.display === 'none') {
                    select.value = '';
                }
            });
        }

        // Appeler la fonction au chargement de la page pour appliquer le filtre initial
        document.addEventListener('DOMContentLoaded', filterExercises);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('exercises-container');

            document.getElementById('add-exercise').addEventListener('click', function () {
                const exerciseTemplate = `
                    <div class="box exercise-box">
                        <div class="field">
                            <label class="label">Exercice</label>
                            <div class="control">
                                <div class="select">
                                    <select name="exercice_ids[]">
                                        <?php foreach ($exercices as $exercice): ?>
                                            <option value="<?= $exercice['id_exercice']; ?>"><?= htmlspecialchars($exercice['nom']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Poids (kg)</label>
                            <div class="control">
                                <input class="input" type="number" step="0.01" name="poids[]" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Répétitions</label>
                            <div class="control">
                                <input class="input" type="number" name="repetitions[]" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Nombre de séries</label>
                            <div class="control">
                                <input class="input" type="number" name="series[]" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Ressenti</label>
                            <div class="control">
                                <input class="input" type="text" name="ressenti[]">
                            </div>
                        </div>
                        <button type="button" class="button is-danger remove-exercise">Supprimer</button>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', exerciseTemplate);
            });

            container.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-exercise')) {
                    e.target.closest('.exercise-box').remove();
                }
            });
        });
    </script>
</body>
</html>