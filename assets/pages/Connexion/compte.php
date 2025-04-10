<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';


// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}


// Récupération de l'id de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Récupération des informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();

// Mise à jour du profil si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = trim($_POST['bio'] ?? '');
    $objectifs_fitness = trim($_POST['objectifs_fitness'] ?? '');
    $objectif = trim($_POST['objectif'] ?? '');
    $photo_profil = !empty($_FILES['photo_profil']['name']) ? 'uploads/profils/' . basename($_FILES['photo_profil']['name']) : ($user['photo_profil'] ?? null);

    // Validation des champs
    if (strlen($bio) > 500) {
        $erreurs[] = "La bio ne peut pas dépasser 500 caractères.";
    }
    if (strlen($objectifs_fitness) > 500) {
        $erreurs[] = "Les objectifs fitness ne peuvent pas dépasser 500 caractères.";
    }
    if (!empty($_FILES['photo_profil']['name'])) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['photo_profil']['type'], $allowed_types)) {
            $erreurs[] = "Le fichier doit être une image (JPEG, PNG ou GIF).";
        }
    }

    // Si aucune erreur, procéder à la mise à jour
    if (empty($erreurs)) {
        if (!empty($_FILES['photo_profil']['name'])) {
            // Vérifier et créer le répertoire si nécessaire
            $upload_dir = '../../uploads/profils';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            move_uploaded_file($_FILES['photo_profil']['tmp_name'], $upload_dir . '/' . basename($_FILES['photo_profil']['name']));
        }

        $update = $pdo->prepare("UPDATE utilisateurs SET bio = :bio, objectifs_fitness = :objectifs_fitness, objectif = :objectif, photo_profil = :photo_profil WHERE id_utilisateur = :id");
        $update->execute([
            'bio' => $bio,
            'objectifs_fitness' => $objectifs_fitness,
            'objectif' => $objectif,
            'photo_profil' => $photo_profil,
            'id' => $user_id
        ]);

        $message = "Profil mis à jour avec succès !";
    }
}

// Récupération des recettes et programmes créés par l'utilisateur
$recettes = $pdo->prepare("SELECT * FROM recettes WHERE id_utilisateur = :id");
$recettes->execute(['id' => $user_id]);
$recettes = $recettes->fetchAll();

$programmes = $pdo->prepare("SELECT * FROM programmes WHERE id_utilisateur = :id");
$programmes->execute(['id' => $user_id]);
$programmes = $programmes->fetchAll();

// Récupération des commandes du client

if (isset($_POST['confirmer_reception'])) {
    $id_commande = $_POST['id_commande'];

    // Mettre à jour le statut de la commande
    $stmt = $pdo->prepare("UPDATE commandes SET statut_commande = 'livrée' WHERE id_commande = ?");
    $stmt->execute([$id_commande]);

    echo "Commande marquée comme livrée.";
}

// Mise à jour des préférences de notifications
if (isset($_POST['update_notifications'])) {
    $notifications_active = isset($_POST['notifications_active']) ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE utilisateurs SET notifications_active = :notifications_active WHERE id_utilisateur = :id");
    $stmt->execute([
        ':notifications_active' => $notifications_active,
        ':id' => $user_id
    ]);

    $message = "Préférences de notifications mises à jour avec succès.";
}

// Récupérer l'état actuel des notifications
$stmt = $pdo->prepare("SELECT notifications_active FROM utilisateurs WHERE id_utilisateur = :id");
$stmt->execute([':id' => $user_id]);
$notifications_active = $stmt->fetchColumn();
?>

<?php include '../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte</title>
</head>
<body>
    <main class="container mt-5">
        <section class="section">
            <div class="box">
                <h1 class="title is-4">Bienvenue, <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?> !</h1>
                <p><strong>Email :</strong> <?= htmlspecialchars($user['email']); ?></p>
                <p><strong>Date de création du compte :</strong> <?= htmlspecialchars($user['date_creation']); ?></p>
                <?php if (!empty($user['photo_profil'])): ?>
                    <img src="../../<?= htmlspecialchars($user['photo_profil']); ?>" alt="Photo de profil" style="max-width: 150px; max-height: 150px;">
                <?php endif; ?>
                <p><strong>Bio :</strong> <?= nl2br(htmlspecialchars($user['bio'] ?? '')); ?></p>
                <p><strong>Objectifs Fitness :</strong> <?= nl2br(htmlspecialchars($user['objectifs_fitness'] ?? '')); ?></p>
                <p><strong>Objectif :</strong> <?= htmlspecialchars($user['objectif'] ?? ''); ?></p>
            </div>
        </section>
        <section class="section">
            <h2 class="title is-5">Préférences de notifications</h2>
            <?php if (isset($message)): ?>
                <div class="notification is-success">
                    <?= htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            <form method="POST">
                <div class="field">
                    <label class="checkbox">
                        <input type="checkbox" name="notifications_active" <?= $notifications_active ? 'checked' : ''; ?>>
                        Activer les notifications
                    </label>
                </div>
                <div class="control">
                    <button type="submit" name="update_notifications" class="button is-primary">Enregistrer</button>
                </div>
            </form>
        </section>
        <!-- Bouton pour afficher/masquer la section -->
        <section class="section">
            <button id="toggle-profile-update" class="button is-primary">Mettre à jour votre profil</button>
        </section>
        <!-- Section Mise à jour du profil -->
        <section id="profile-update-section" class="section" style="display: none;">
            <h2 class="title is-5">Modifier votre profil</h2>
            <?php if (isset($message)): ?>
                <div class="notification is-success">
                    <?= htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data" class="box">
                <div class="field">
                    <label class="label">Photo de profil</label>
                    <div class="control">
                        <input class="input" type="file" name="photo_profil">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Bio</label>
                    <div class="control">
                        <textarea class="textarea" name="bio" placeholder="Parlez de vous..."><?= htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Objectifs Fitness</label>
                    <div class="control">
                        <textarea class="textarea" name="objectifs_fitness" placeholder="Quels sont vos objectifs fitness ?"><?= htmlspecialchars($user['objectifs_fitness'] ?? ''); ?></textarea>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Objectif</label>
                    <div class="control">
                        <input class="input" type="text" name="objectif" value="<?= htmlspecialchars($user['objectif'] ?? ''); ?>" placeholder="Votre objectif (ex: Prise de masse)">
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <button type="submit" class="button is-primary">Mettre à jour</button>
                    </div>
                </div>
            </form>
        </section>
       <!-- Section Commandes -->
       <section class="section">
            <h2 class="title is-5">Vos Commandes</h2>
            <?php
            // Récupérer toutes les commandes de l'utilisateur
            $stmt = $pdo->prepare("SELECT * FROM commandes WHERE id_utilisateur = ? ORDER BY date_commande DESC");
            $stmt->execute([$user_id]);
            $commandes = $stmt->fetchAll();
            ?>
            <?php if (!empty($commandes)): ?>
                <table class="table is-striped is-fullwidth">
                    <thead>
                        <tr>
                            <th>Numéro de commande</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($commandes as $commande): ?>
                            <tr id="commande-<?= $commande['id_commande']; ?>">
                                <td><?= 'CMD-' . str_pad($commande['id_commande'], 6, '0', STR_PAD_LEFT); ?></td>
                                <td><?= htmlspecialchars($commande['date_commande']); ?></td>
                                <td><?= number_format($commande['total'], 2, ',', ' '); ?> €</td>
                                <td><?= htmlspecialchars($commande['statut_commande']); ?></td>
                                <td>
                                    <?php if ($commande['statut_commande'] === 'en attente'): ?>
                                        <form method="POST" class="delete-commande-form" data-id="<?= $commande['id_commande']; ?>" style="display: inline;">
                                            <input type="hidden" name="id_commande" value="<?= $commande['id_commande']; ?>">
                                            <button type="submit" name="supprimer_commande" class="button is-danger is-small">Supprimer</button>
                                        </form>
                                    <?php elseif ($commande['statut_commande'] === 'en expédition'): ?>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="id_commande" value="<?= $commande['id_commande']; ?>">
                                            <button type="submit" name="confirmer_reception" class="button is-success is-small">Confirmer réception</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="tag is-light">Aucune action</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Vous n'avez passé aucune commande.</p>
            <?php endif; ?>
        </section>
        <!-- Affichage des recettes créées par l'utilisateur -->
        <section class="section">
            <h2 class="title is-5">Vos Recettes</h2>
            <?php if (!empty($recettes)): ?>
                <div class="columns is-multiline is-variable is-4">
                    <?php foreach ($recettes as $recette): ?>
                        <div class="column is-one-quarter-desktop is-half-tablet">
                            <div class="card recipe-card">
                                <!-- Image de la recette -->
                                <div class="card-image">
                                    <figure class="image is-4by3">
                                        <?php if (!empty($recette['image'])): ?>
                                            <img src="../../<?= htmlspecialchars($recette['image']); ?>" 
                                                alt="<?= htmlspecialchars($recette['titre']); ?>">
                                        <?php else: ?>
                                            <img src="../../images/default-recipe.jpg" 
                                                alt="Image par défaut">
                                        <?php endif; ?>
                                    </figure>
                                </div>
                                <!-- Contenu de la recette -->
                                <div class="card-content">
                                    <p class="title is-5"><?= htmlspecialchars($recette['titre']); ?></p>
                                    <p class="subtitle is-6"><?= htmlspecialchars($recette['categorie']); ?></p>
                                    <p><strong>Date :</strong> <?= htmlspecialchars($recette['date_creation']); ?></p>
                                    <p class="description">
                                        <?= htmlspecialchars(substr($recette['description'], 0, 100)) . '...'; ?>
                                    </p>
                                </div>
                                <!-- Bouton pour voir la recette -->
                                <footer class="card-footer">
                                    <a href="../recette/detail_recette.php?id=<?= $recette['id_recette']; ?>" class="card-footer-item button is-link">
                                        Voir la recette
                                    </a>
                                </footer>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Vous n'avez créé aucune recette.</p>
            <?php endif; ?>
        </section>

        <!-- Affichage des programmes créés par l'utilisateur -->
        <section class="section">
            <h2 class="title is-5">Vos Programmes</h2>
            <?php if (!empty($programmes)): ?>
                <div class="columns is-multiline is-variable is-4">
                    <?php foreach ($programmes as $programme): ?>
                        <div class="column is-one-quarter-desktop is-half-tablet">
                            <div class="card program-card">
                                <!-- Image du programme -->
                                <div class="card-image">
                                    <figure class="image is-4by3">
                                        <?php if (!empty($programme['image'])): ?>
                                            <img src="../../<?= htmlspecialchars($programme['image']); ?>" 
                                                alt="<?= htmlspecialchars($programme['titre']); ?>">
                                        <?php else: ?>
                                            <img src="../../images/default-program.jpg" 
                                                alt="Image par défaut">
                                        <?php endif; ?>
                                    </figure>
                                </div>
                                <!-- Contenu du programme -->
                                <div class="card-content">
                                    <p class="title is-5"><?= htmlspecialchars($programme['titre']); ?></p>
                                    <p><strong>Date :</strong> <?= htmlspecialchars($programme['date_creation']); ?></p>
                                    <p class="description">
                                        <?= htmlspecialchars(substr($programme['description'], 0, 100)) . '...'; ?>
                                    </p>
                                </div>
                                <!-- Bouton pour voir le programme -->
                                <footer class="card-footer">
                                    <a href="../programme/detail_programme.php?id=<?= $programme['id_programme']; ?>" class="card-footer-item button is-link">
                                        Voir le programme
                                    </a>
                                </footer>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Vous n'avez créé aucun programme.</p>
            <?php endif; ?>
        </section>

        <!-- Bouton de déconnexion -->
        <section class="section">
            <a href="../Connexion/deconnexion.php" class="button is-danger">Se déconnecter</a>
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
<script>
    document.getElementById('toggle-profile-update').addEventListener('click', function () {
        const section = document.getElementById('profile-update-section');
        if (section.style.display === 'none' || section.style.display === '') {
            section.style.display = 'block';
            this.textContent = 'Masquer Modifier votre profil';
        } else {
            section.style.display = 'none';
            this.textContent = 'Modifier votre profil';
        }
    });
</script>
<script>
        // Gestion de la suppression des commandes
    const deleteForms = document.querySelectorAll('.delete-commande-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const commandeId = form.dataset.id;
                const formData = new FormData(form);

                try {
                    const response = await fetch('', {
                        method: 'POST',
                        body: formData
                    });

                    if (response.ok) {
                        // Supprimer la ligne du tableau
                        const row = document.getElementById(`commande-${commandeId}`);
                        if (row) row.remove();

                        // Afficher une notification
                        const notification = document.createElement('div');
                        notification.className = 'notification is-success';
                        notification.textContent = 'Commande supprimée avec succès.';
                        document.body.appendChild(notification);

                        // Supprimer la notification après 3 secondes
                        setTimeout(() => notification.remove(), 3000);
                    } else {
                        alert('Erreur lors de la suppression de la commande.');
                    }
                } catch (error) {
                    console.error('Erreur :', error);
                }
            });
        });
</script>
</html>