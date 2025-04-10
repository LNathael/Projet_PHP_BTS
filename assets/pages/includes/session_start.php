<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$timeout_duration = 1800;

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: ../Connexion/connexion.php?timeout=1");
    exit;
}

$_SESSION['last_activity'] = time();

date_default_timezone_set('Europe/Paris');

require_once '../config/db.php';

$isConnected = isset($_SESSION['user_id']);
$user = null;

if ($isConnected) {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = :id");
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch();
}
?>

<!-- Loader pour les actions -->
<div id="action-loader" class="loading-overlay is-hidden">
    <div class="loading-spinner"></div>
</div>

<script>
    const deleteForms = document.querySelectorAll('.delete-commande-form');
    const actionLoader = document.getElementById('action-loader');

    deleteForms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const commandeId = form.dataset.id;
            const formData = new FormData(form);

            // Afficher le loader
            actionLoader.classList.remove('is-hidden');

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
                alert('Une erreur est survenue. Veuillez réessayer.');
            } finally {
                // Masquer le loader
                actionLoader.classList.add('is-hidden');
            }
        });
    });
</script>