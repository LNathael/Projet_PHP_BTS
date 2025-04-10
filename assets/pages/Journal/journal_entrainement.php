    <?php
session_start();
require_once '../config/db.php'; // Inclure la connexion à la base de données

if (!isset($_SESSION['user_id'])) {
    header('Location: ../Connexion/connexion.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$erreurs = [];

// Récupérer les données pour les graphiques
$stmt = $pdo->prepare("
    SELECT seance_exercice.date AS date_seance, 
           AVG(seance_exercice.poids * seance_exercice.repetitions * seance_exercice.series) AS energie
    FROM seance_exercice 
    JOIN entrainements ON seance_exercice.id_entrainement = entrainements.id_entrainement 
    WHERE entrainements.id_utilisateur = :id_utilisateur 
    GROUP BY seance_exercice.date 
    ORDER BY seance_exercice.date;
");
$stmt->execute([':id_utilisateur' => $user_id]);
$performances = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les séances pour le calendrier
$stmt = $pdo->prepare("
    SELECT id_entrainement, titre, date 
    FROM entrainements 
    WHERE id_utilisateur = :id_utilisateur 
    ORDER BY date;
");
$stmt->execute([':id_utilisateur' => $user_id]);
$seances = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la dernière séance
$stmt = $pdo->prepare("
    SELECT se.id_entrainement, e.nom, se.poids, se.repetitions, se.series, se.ressenti 
    FROM seance_exercice se
    JOIN exercices e ON se.id_exercice = e.id_exercice
    WHERE se.id_entrainement = (
        SELECT MAX(id_entrainement) 
        FROM entrainements 
        WHERE id_utilisateur = :id_utilisateur
    )
");
$stmt->execute([':id_utilisateur' => $user_id]);
$derniere_seance = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les records de poids par exercice
$stmt = $pdo->prepare("
    SELECT e.nom, MAX(se.poids) AS record_poids 
    FROM seance_exercice se
    JOIN exercices e ON se.id_exercice = e.id_exercice
    WHERE se.id_entrainement IN (
        SELECT id_entrainement 
        FROM entrainements 
        WHERE id_utilisateur = :id_utilisateur
    )
    GROUP BY e.nom
");
$stmt->execute([':id_utilisateur' => $user_id]);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
$stmt = $pdo->prepare("SELECT id_utilisateur, nom, prenom FROM utilisateurs WHERE id_utilisateur = :id_utilisateur");
$stmt->execute(['id_utilisateur' => $user_id]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
?>
 <?php include '../includes/header.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal d'Entraînement</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.js"></script>
    
</head>

<body>
    <section class="section">
        <div class="container">
            <h1 class="title">Journal d'Entraînement</h1>

            <div class="buttons">
                <a href="ajouter_exercice.php" class="button is-primary">Ajouter un Exercice</a>
                <a href="ajouter_seance.php" class="button is-info">Ajouter une Séance</a>
                <a href="liste_exercices.php" class="button is-warning">Liste des Exercices</a>
                <a href="seances_effectuees.php" class="button is-success">Séances Effectuées</a>
            </div>

            <!-- Graphique Énergie -->
            <section class="section">
                <h2 class="title">Énergie dépensée</h2>
                <canvas id="energieChart"></canvas>
            </section>

            <!-- Aperçu de la dernière séance -->
            <section class="section">
                <h2 class="title">Dernière Séance</h2>
                <?php if ($derniere_seance): ?>
                    <table class="table is-striped is-fullwidth">
                        <thead>
                            <tr>
                                <th>Exercice</th>
                                <th>Poids (kg)</th>
                                <th>Répétitions</th>
                                <th>Séries</th>
                                <th>Ressenti</th>
                                <td>voir_client</td>
                                <td>voir_seance</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($derniere_seance as $seance): ?>
                                <tr>
                                    <td><?= htmlspecialchars($seance['nom']); ?></td>
                                    <td><?= htmlspecialchars($seance['poids']); ?></td>
                                    <td><?= htmlspecialchars($seance['repetitions']); ?></td>
                                    <td><?= htmlspecialchars($seance['series']); ?></td>
                                    <td><?= htmlspecialchars($seance['ressenti']); ?></td>
                                    
                                    <td>
                                        <a href="voir_client.php?id=<?= $utilisateur['id_utilisateur']; ?>" class="button is-link is-small">Voir Client</a>
                                    </td>
                                    <td>
                                        <a href="voir_seance.php?id=<?= $seance['id_entrainement']; ?>" class="button is-link is-small">Voir Séance</a>
                                    </td>    
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Aucune séance récente trouvée.</p>
                <?php endif; ?>
            </section>

            <!-- Calendrier des séances -->
            <section class="section">
                <h2 class="title">Calendrier des Séances</h2>
                <div id="calendar"></div>
            </section>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const calendarEl = document.getElementById('calendar');
                    const calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        locale: 'fr', // Définit la langue en français
                        events: <?= json_encode(array_map(function($seance) {
                            return [
                                'title' => $seance['titre'],
                                'start' => $seance['date'],
                                'url' => 'voir_seance.php?id=' . $seance['id_entrainement']
                            ];
                        }, $seances)); ?>
                    });
                    calendar.render();
                });
            </script>

            <!-- Records -->
            <section class="section">
                <h2 class="title">Records</h2>
                <table class="table is-striped is-fullwidth">
                    <thead>
                        <tr>
                            <th>Exercice</th>
                            <th>Record de Poids (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $record): ?>
                            <tr>
                                <td><?= htmlspecialchars($record['nom']); ?></td>
                                <td>
                                    <?= htmlspecialchars($record['record_poids']); ?>
                                    <span class="icon has-text-warning">
                                        <i class="fas fa-crown"></i>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section><style>
                .fa-crown {
                    margin-left: 5px;
                    font-size: 1.2em;
                }
            </style>

            <!-- Bouton retour -->
            <a href="../Acceuil/accueil.php" class="button is-light mt-5">
                <span class="icon">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span>Retour à l'accueil</span>
            </a>
        </div>
    </section>
    <?php include '../includes/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialiser le graphique Énergie
            const ctx = document.getElementById('energieChart').getContext('2d');
            const performances = <?= json_encode($performances); ?>;
            const labels = performances.map(performance => performance.date_seance);
            const data = performances.map(performance => performance.energie);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Énergie dépensée',
                        data: data,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    }
                }
            });

            // Initialiser le calendrier
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: <?= json_encode(array_map(function($seance) {
                    return [
                        'title' => $seance['titre'],
                        'start' => $seance['date'],
                        'url' => 'voir_seance.php?id=' . $seance['id_entrainement']
                    ];
                }, $seances)); ?>
            });
            calendar.render();
        });
    </script>
</body>
</html>