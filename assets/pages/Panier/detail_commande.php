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

$id_utilisateur = $_SESSION['user_id'];

// Vérifier si l'ID de la commande est passé en paramètre
if (!isset($_GET['id_commande'])) {
    die("ID de commande non spécifié.");
}

$id_commande = intval($_GET['id_commande']);

// Récupérer les détails de la commande
$stmt = $pdo->prepare("
    SELECT c.id_commande, c.date_commande, c.total, c.statut_commande, 
           d.id_produit, d.quantite, d.prix_unitaire, 
           p.nom_produit, p.image
    FROM commandes c
    JOIN details_commande d ON c.id_commande = d.id_commande
    JOIN produits p ON d.id_produit = p.id_produit
    WHERE c.id_commande = :id_commande AND c.id_utilisateur = :id_utilisateur
");
$stmt->execute(['id_commande' => $id_commande, 'id_utilisateur' => $id_utilisateur]);
$details_commande = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Vérifier si la commande existe
if (empty($details_commande)) {
    die("Commande introuvable ou vous n'avez pas les droits pour y accéder.");
}

// Récupérer les informations générales de la commande
$commande = $details_commande[0];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail de la commande</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="title">Détail de la commande n°<?= 'CMD-' . str_pad($commande['id_commande'], 6, '0', STR_PAD_LEFT); ?></h1>
        <p><strong>Date :</strong> <?= htmlspecialchars($commande['date_commande']); ?></p>
        <p><strong>Statut :</strong> <?= htmlspecialchars($commande['statut_commande']); ?></p>
        <p><strong>Total :</strong> <?= number_format($commande['total'], 2, ',', ' '); ?> €</p>

        <h2 class="title is-4 mt-5">Produits commandés</h2>
        <table class="table is-striped is-fullwidth">
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
                <?php foreach ($details_commande as $detail): ?>
                    <tr>
                        <td>
                            <?php if (!empty($detail['image'])): ?>
                                <img src="../../<?= htmlspecialchars($detail['image']); ?>" alt="<?= htmlspecialchars($detail['nom_produit']); ?>" style="width: 100px;">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($detail['nom_produit']); ?></td>
                        <td><?= $detail['quantite']; ?></td>
                        <td><?= number_format($detail['prix_unitaire'], 2, ',', ' '); ?> €</td>
                        <td><?= number_format($detail['quantite'] * $detail['prix_unitaire'], 2, ',', ' '); ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="../Connexion/compte.php" class="button is-link mt-5">Retour à mon compte</a>
    </div>
</body>
</html>