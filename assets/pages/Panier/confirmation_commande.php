<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Connexion/connexion.php');
    exit;
}

$id_commande = $_GET['id_commande'] ?? null;
if (!$id_commande) {
    header('Location: ../Magasin/magasin.php');
    exit;
}

// Récupérer les informations de la commande
$stmt_commande = $pdo->prepare("
    SELECT c.id_commande, c.total, c.date_commande, c.statut_commande
    FROM commandes c
    WHERE c.id_commande = ? AND c.id_utilisateur = ?
");
$stmt_commande->execute([$id_commande, $_SESSION['user_id']]);
$commande = $stmt_commande->fetch();

if (!$commande) {
    die('Commande introuvable.');
}

// Récupérer les détails de la commande
$stmt_details = $pdo->prepare("
    SELECT d.id_produit, d.quantite, d.prix_unitaire, p.nom_produit, p.image
    FROM details_commande d
    JOIN produits p ON d.id_produit = p.id_produit
    WHERE d.id_commande = ?
");
$stmt_details->execute([$id_commande]);
$details = $stmt_details->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande Confirmée</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="title">Commande Confirmée</h1>
        <p>Votre commande a été enregistrée avec succès. Elle est en attente de validation par un administrateur.</p>

        <!-- Récapitulatif de la commande -->
        <div class="box">
            <h2 class="subtitle">Récapitulatif de la commande</h2>
            <p><strong>Numéro de commande :</strong> <?= htmlspecialchars($commande['id_commande']); ?></p>
            <p><strong>Date :</strong> <?= htmlspecialchars($commande['date_commande']); ?></p>
            <p><strong>Statut :</strong> <?= htmlspecialchars($commande['statut_commande']); ?></p>
            <p><strong>Total :</strong> <?= number_format($commande['total'], 2, ',', ' '); ?> €</p>

            <table class="table is-striped is-fullwidth mt-4">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix Unitaire</th>
                        <th>Sous-total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($details as $item): ?>
                        <tr>
                            <td>
                                <?php if (!empty($item['image'])): ?>
                                    <img src="../../<?= htmlspecialchars($item['image']); ?>" alt="<?= htmlspecialchars($item['nom_produit']); ?>" style="width: 100px;">
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($item['nom_produit']); ?></td>
                            <td><?= $item['quantite']; ?></td>
                            <td><?= number_format($item['prix_unitaire'], 2, ',', ' '); ?> €</td>
                            <td><?= number_format($item['prix_unitaire'] * $item['quantite'], 2, ',', ' '); ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Bouton pour retourner au compte -->
        <a href="../Connexion/compte.php" class="button is-link">Retour à votre compte</a>
    </div>
</body>
</html>