<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Connexion/connexion.php');
    exit;
}

$id_utilisateur = $_SESSION['user_id'];

// Récupérer les produits du panier
$stmt = $pdo->prepare("
    SELECT p.nom_produit, p.prix, p.image, pa.quantite, pa.id_produit
    FROM panier pa
    JOIN produits p ON pa.id_produit = p.id_produit
    WHERE pa.id_utilisateur = ?
");
$stmt->execute([$id_utilisateur]);
$panier = $stmt->fetchAll();

// Calculer le total
$total = 0;
foreach ($panier as $item) {
    $total += $item['prix'] * $item['quantite'];
}

// Si le formulaire est soumis, créer une commande
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insérer la commande
    $stmt = $pdo->prepare("INSERT INTO commandes (id_utilisateur, total, statut_commande) VALUES (?, ?, 'en attente')");
    $stmt->execute([$id_utilisateur, $total]);
    $id_commande = $pdo->lastInsertId();

    
    // Insérer les détails de la commande
    foreach ($panier as $item) {
        $stmt = $pdo->prepare("INSERT INTO details_commande (id_commande, id_produit, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id_commande, $item['id_produit'], $item['quantite'], $item['prix']]);
    }
    $autoloadPath = realpath('../../../vendor/autoload.php');
    if (!$autoloadPath) {
        die('Le fichier autoload.php est introuvable. Chemin vérifié : ' . realpath('../../vendor/'));
    }
    require $autoloadPath;


    // Après l'insertion de la commande
    $stmt = $pdo->prepare("INSERT INTO commandes (id_utilisateur, total, statut_commande) VALUES (?, ?, 'en attente')");
    $stmt->execute([$id_utilisateur, $total]);
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'nathael.lebihan12102005@gmail.com'; // Remplacez par votre email
        $mail->Password = 'tvxw btll ohvs kldr'; // Remplacez par votre mot de passe d'application
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('nathael.lebihan12102005@gmail.com', 'NutriStrong');
        $mail->addAddress($user_email, $user_name); // Email et nom de l'utilisateur

        $mail->isHTML(true);
        $mail->Subject = 'Confirmation de votre commande';
        $mail->Body = "Bonjour $user_name,<br><br>Votre commande (n°CMD-" . str_pad($id_commande, 6, '0', STR_PAD_LEFT) . ") a été créée avec succès. Elle est en attente de validation.<br><br>Merci pour votre confiance.";

        $mail->send();
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
    }



    // Vider le panier
    $stmt = $pdo->prepare("DELETE FROM panier WHERE id_utilisateur = ?");
    $stmt->execute([$id_utilisateur]);

    // Rediriger vers la page de confirmation
    header('Location: confirmation_commande.php?id_commande=' . $id_commande);
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation de la commande</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="title">Validation de la commande</h1>
        <table class="table is-striped is-fullwidth">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Produit</th>
                    <th>Prix Unitaire</th>
                    <th>Quantité</th>
                    <th>Sous-total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($panier as $item): ?>
                    <tr>
                        <td>
                            <?php if (!empty($item['image'])): ?>
                                <img src="../../../<?= htmlspecialchars($item['image']); ?>" alt="<?= htmlspecialchars($item['nom_produit']); ?>" style="width: 100px;">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($item['nom_produit']); ?></td>
                        <td><?= number_format($item['prix'], 2); ?> €</td>
                        <td><?= $item['quantite']; ?></td>
                        <td><?= number_format($item['prix'] * $item['quantite'], 2); ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="has-text-right">Total :</th>
                    <th><?= number_format($total, 2); ?> €</th>
                </tr>
            </tfoot>
        </table>

        <form method="POST">
            <button type="submit" class="button is-primary">Confirmer la commande</button>
        </form>
    </div>
</body>
</html>