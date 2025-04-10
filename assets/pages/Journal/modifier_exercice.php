<?php
session_start();
require_once '../config/db.php';

// Vérifier si l'utilisateur est connecté et est administrateur
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'administrateur' && $_SESSION['role'] !== 'super_administrateur')) {
    header('Location: ../Connexion/connexion.php');
    exit;
}

$erreurs = [];
$nom = '';
$description = '';
$image_path = '';
$video_path = '';
$categorie = '';

// Récupérer l'ID de l'exercice
$id_exercice = $_GET['id'] ?? null;
if (!$id_exercice) {
    die('Exercice introuvable.');
}

// Récupérer les données de l'exercice
$stmt = $pdo->prepare("SELECT * FROM exercices WHERE id_exercice = :id");
$stmt->execute(['id' => $id_exercice]);
$exercice = $stmt->fetch();

if (!$exercice) {
    die('Exercice introuvable.');
}

// Récupérer les catégories existantes
$categories = $pdo->query("SELECT DISTINCT categorie FROM exercices WHERE categorie IS NOT NULL")->fetchAll(PDO::FETCH_ASSOC);

// Traiter le formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $categorie = trim($_POST['categorie'] ?? '');

    // Validation
    if (empty($nom)) {
        $erreurs[] = "Le nom de l'exercice est obligatoire.";
    }

    // Gérer l'upload de l'image
    if (!empty($_FILES['image']['name'])) {
        $image_path = '../../uploads/' . basename($_FILES['image']['name']);
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $erreurs[] = "Erreur lors de l'upload de l'image.";
        }
    } else {
        $image_path = $exercice['image_path'];
    }

    // Gérer l'upload de la vidéo
    if (!empty($_FILES['video']['name'])) {
        $video_path = '../../uploads/' . basename($_FILES['video']['name']);
        if (!move_uploaded_file($_FILES['video']['tmp_name'], $video_path)) {
            $erreurs[] = "Erreur lors de l'upload de la vidéo.";
        }
    } else {
        $video_path = $exercice['video_path'];
    }

    // Mettre à jour l'exercice dans la base de données
    if (empty($erreurs)) {
        $stmt = $pdo->prepare("UPDATE exercices SET nom = :nom, description = :description, image_path = :image_path, video_path = :video_path, categorie = :categorie WHERE id_exercice = :id");
        $stmt->execute([
            ':nom' => $nom,
            ':description' => $description,
            ':image_path' => $image_path,
            ':video_path' => $video_path,
            ':categorie' => $categorie,
            ':id' => $id_exercice
        ]);
        header('Location: liste_exercices.php');
        exit;
    }
}
?>
<?php include '../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Exercice</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <section class="section">
        <div class="container">
            <h1 class="title">Modifier un Exercice</h1>

            <!-- Affichage des erreurs -->
            <?php if (!empty($erreurs)): ?>
                <div class="notification is-danger">
                    <?php foreach ($erreurs as $erreur): ?>
                        <p><?= htmlspecialchars($erreur); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Formulaire de modification -->
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="field">
                    <label class="label">Nom</label>
                    <div class="control">
                        <input class="input" type="text" name="nom" value="<?= htmlspecialchars($exercice['nom']); ?>" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Description</label>
                    <div class="control">
                        <textarea class="textarea" name="description"><?= htmlspecialchars($exercice['description']); ?></textarea>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Catégorie</label>
                    <div class="control">
                        <div class="select">
                            <select name="categorie">
                                <option value="">-- Sélectionnez une catégorie --</option>
                                <?php foreach ($categories as $categorie): ?>
                                    <option value="<?= htmlspecialchars($categorie['categorie']); ?>" <?= $exercice['categorie'] === $categorie['categorie'] ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($categorie['categorie']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Image</label>
                    <div class="control">
                        <input class="input" type="file" name="image">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Vidéo</label>
                    <div class="control">
                        <input class="input" type="file" name="video">
                    </div>
                </div>
                <div class="control">
                    <button class="button is-link" type="submit">Modifier</button>
                </div>
            </form>
        </div>
    </section>
</body>
</html>
<?php include '../includes/footer.php'; ?>