<?php
session_start();
require_once '../config/db.php'; // Inclure la connexion à la base de données

if (!isset($_SESSION['user_id'])) {
    header('Location: ../Connexion/connexion.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer les filtres
$nombre_exercices = $_GET['nombre_exercices'] ?? null;
$mois = $_GET['mois'] ?? null;

// Construire la requête SQL avec les filtres
$sql = "
    SELECT e.id_entrainement, e.titre, e.date, e.description, COUNT(se.id_seance_exercice) AS nombre_exercices
    FROM entrainements e
    LEFT JOIN seance_exercice se ON e.id_entrainement = se.id_entrainement
    WHERE e.id_utilisateur = :id_utilisateur
";

$params = [':id_utilisateur' => $user_id];

// Ajouter un filtre par mois
if ($mois) {
    $sql .= " AND MONTH(e.date) = :mois";
    $params[':mois'] = $mois;
}

// Ajouter un filtre par nombre d'exercices
if ($nombre_exercices) {
    $sql .= " GROUP BY e.id_entrainement HAVING COUNT(se.id_seance_exercice) = :nombre_exercices";
    $params[':nombre_exercices'] = $nombre_exercices;
} else {
    $sql .= " GROUP BY e.id_entrainement";
}

// Trier par date décroissante
$sql .= " ORDER BY e.date DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$seances = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les mois disponibles pour le filtre
$mois_disponibles = $pdo->query("
    SELECT DISTINCT MONTH(date) AS mois, YEAR(date) AS annee 
    FROM entrainements 
    WHERE id_utilisateur = $user_id
    ORDER BY annee DESC, mois DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Séances Effectuées</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <section class="section">
        <div class="container">
            <h1 class="title">Séances Effectuées</h1>

            <!-- Formulaire de filtres -->
            <form method="GET" action="" class="mb-4">
                <div class="field is-grouped">
                    <!-- Filtre par mois -->
                    <div class="control">
                        <div class="select">
                            <select name="mois">
                                <option value="">-- Filtrer par mois --</option>
                                <?php foreach ($mois_disponibles as $mois_disponible): ?>
                                    <option value="<?= $mois_disponible['mois']; ?>" <?= ($mois == $mois_disponible['mois']) ? 'selected' : ''; ?>>
                                        <?= date('F Y', mktime(0, 0, 0, $mois_disponible['mois'], 1, $mois_disponible['annee'])); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    

                    <!-- Filtre par nombre d'exercices -->
                    <div class="control">
                        <div class="select">
                            <select name="nombre_exercices">
                                <option value="">-- Filtrer par nombre d'exercices --</option>
                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                    <option value="<?= $i; ?>" <?= ($nombre_exercices == $i) ? 'selected' : ''; ?>>
                                        <?= $i; ?> exercice<?= $i > 1 ? 's' : ''; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Bouton de soumission -->
                    <div class="control">
                        <button class="button is-primary" type="submit">Filtrer</button>
                    </div>
                </div>
            </form>

            <!-- Tableau des séances -->
            <?php if ($seances): ?>
                <table class="table is-striped is-fullwidth">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Nombre d'exercices</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($seances as $seance): ?>
                            <tr>
                                <td><?= htmlspecialchars($seance['date']); ?></td>
                                <td><?= htmlspecialchars($seance['titre']); ?></td>
                                <td><?= htmlspecialchars($seance['description'] ?? 'Aucune description'); ?></td>
                                <td><?= htmlspecialchars($seance['nombre_exercices']); ?></td>
                                <td>
                                    <a href="voir_seance.php?id=<?= $seance['id_entrainement']; ?>" class="button is-link is-small">Voir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucune séance correspondant aux critères de recherche.</p>
            <?php endif; ?>
        </div>
    </section>
    <?php include '../includes/footer.php'; ?>
</body>
</html>