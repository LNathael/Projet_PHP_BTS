<?php
session_start();
require_once '../config/db.php';
require_once '../../../vendor/autoload.php'; // Inclure l'autoloader de Composer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include '../includes/session_start.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

if ($_SESSION['role'] !== 'administrateur' && $_SESSION['role'] !== 'super_administrateur') {
    header('Location: erreur_403.php'); // Page interdite
    exit;
}

$isSuperAdmin = ($_SESSION['role'] === 'super_administrateur');


// Gestion utilisateur
// Supprimer un utilisateur
if ($isSuperAdmin && isset($_POST['delete_user'])) {
    $id_utilisateur = (int)$_POST['id_utilisateur'];
    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = :id");
    $stmt->execute(['id' => $id_utilisateur]);
}

// Modifier un utilisateur
if ($isSuperAdmin && isset($_POST['edit_user'])) {
    $id_utilisateur = (int)$_POST['id_utilisateur'];
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $role = $_POST['role'];

    if ($role !== 'super_administrateur' || $isSuperAdmin) { // Empêche la modification du rôle super administrateur
        $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = :nom, prenom = :prenom, email = :email, role = :role WHERE id_utilisateur = :id");
        $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'role' => $role,
            'id' => $id_utilisateur,
        ]);
    }
}

// Création d'un administrateur par le super administrateur
if ($isSuperAdmin && isset($_POST['create_admin'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $role = 'administrateur';

    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES (:nom, :prenom, :email, :mot_de_passe, :role)");
    $stmt->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'mot_de_passe' => $mot_de_passe,
        'role' => $role
    ]);
}

// AVIS

// Supprimer un avis
if (isset($_POST['delete_avis'])) {
    $id_avis = (int)$_POST['id_avis'];
    $stmt = $pdo->prepare("DELETE FROM avis WHERE id_avis = :id");
    $stmt->execute(['id' => $id_avis]);
}

// Modifier un avis
if (isset($_POST['edit_avis'])) {
    $id_avis = (int)$_POST['id_avis'];
    $commentaire = htmlspecialchars($_POST['commentaire']);
    $note = (int)$_POST['note'];

    $stmt = $pdo->prepare("UPDATE avis SET commentaire = :commentaire, note = :note WHERE id_avis = :id");
    $stmt->execute([
        'commentaire' => $commentaire,
        'note' => $note,
        'id' => $id_avis,
    ]);
}

// Recette 

// Supprimer une recette
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_recette'])) {
    $id_recette = intval($_POST['id_recette']);

    // Supprimer l'image associée
    $stmt = $pdo->prepare("SELECT image FROM recettes WHERE id_recette = ?");
    $stmt->execute([$id_recette]);
    $recette = $stmt->fetch();
    if ($recette && !empty($recette['image']) && file_exists("../../" . $recette['image'])) {
        unlink("../../" . $recette['image']); // Supprimer l'image du serveur
    }

    // Supprimer la recette de la base de données
    $stmt = $pdo->prepare("DELETE FROM recettes WHERE id_recette = ?");
    $stmt->execute([$id_recette]);
    echo "<div class='notification is-success'>Recette supprimée avec succès.</div>";
}

// Modifier une recette
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_recette'])) {
    $id_recette = intval($_POST['id_recette']);
    $titre = htmlspecialchars($_POST['titre'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');
    $categorie = htmlspecialchars($_POST['categorie'] ?? '');
    $ingredients = htmlspecialchars($_POST['ingredients'] ?? '');
    $etapes = htmlspecialchars($_POST['etapes'] ?? '');
    $imagePath = null;

    // Gestion de l'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../uploads/recettes/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $imagePath = $uploadDir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    // Mise à jour de la recette
    $sql = "UPDATE recettes SET titre = ?, description = ?, categorie = ?, ingredients = ?, etapes = ?";
    $params = [$titre, $description, $categorie, $ingredients, $etapes];

    if ($imagePath) {
        $sql .= ", image = ?";
        $params[] = $imagePath;
    }

    $sql .= " WHERE id_recette = ?";
    $params[] = $id_recette;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    echo "<div class='notification is-success'>Recette modifiée avec succès.</div>";
}

//Produit
// Récupérer les données du produit
$utilisateurs = $pdo->query("SELECT * FROM utilisateurs ORDER BY date_creation DESC")->fetchAll(PDO::FETCH_ASSOC);
$avis = $pdo->query("SELECT a.*, u.nom AS nom_utilisateur, u.prenom AS prenom_utilisateur 
                     FROM avis a 
                     JOIN utilisateurs u ON a.id_utilisateur = u.id_utilisateur 
                     ORDER BY a.date_avis DESC")->fetchAll(PDO::FETCH_ASSOC);
$recettes = $pdo->query("SELECT r.*, u.nom AS nom_utilisateur, u.prenom AS prenom_utilisateur 
                         FROM recettes r 
                         JOIN utilisateurs u ON r.id_utilisateur = u.id_utilisateur 
                         ORDER BY r.date_creation DESC")->fetchAll(PDO::FETCH_ASSOC);

// Ajouter un produit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $nom = htmlspecialchars($_POST['nom'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');
    $prix = floatval($_POST['prix'] ?? 0);
    $quantite = intval($_POST['quantite'] ?? 0);
    $libelle = htmlspecialchars($_POST['libelle'] ?? '');
    if (strlen($libelle) > 255) { // Remplacez 255 par la taille maximale de la colonne
        echo "Le libellé est trop long. Veuillez utiliser un texte de 255 caractères maximum.";
        exit;
    }
    $imagePath = null;

    // Validation des champs obligatoires
    if (empty($nom) || empty($description) || $prix <= 0 || $quantite <= 0 || empty($libelle)) {
        echo "Veuillez remplir tous les champs correctement.";
    } else {
        // Gestion de l'image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../uploads/produits/';
            $imageName = uniqid('produit_') . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName)) {
                $imagePath = 'uploads/produits/' . $imageName;
            }
        }

        // Insérer le produit dans la base de données
        $stmt = $pdo->prepare("INSERT INTO produits (nom_produit, description, prix, quantite_disponible, libelle, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $description, $prix, $quantite, $libelle, $imagePath]);
        echo "Produit ajouté avec succès.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}



// Supprimer un produit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $id_produit = intval($_POST['id_produit']);

    // Supprimer l'image associée
    $stmt = $pdo->prepare("SELECT image FROM produits WHERE id_produit = ?");
    $stmt->execute([$id_produit]);
    $produit = $stmt->fetch();
    if ($produit && !empty($produit['image']) && file_exists("../../" . $produit['image'])) {
        unlink("../../" . $produit['image']); // Supprimer l'image du serveur
    }

    // Supprimer le produit de la base de données
    $stmt = $pdo->prepare("DELETE FROM produits WHERE id_produit = ?");
    $stmt->execute([$id_produit]);
    echo "<div class='notification is-success'>Produit supprimé avec succès.</div>";
}

// Modifier un produit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product'])) {
    $id_produit = intval($_POST['id_produit']);
    $nom = htmlspecialchars($_POST['nom']);
    $description = htmlspecialchars($_POST['description']);
    $prix = floatval($_POST['prix']);
    $quantite = intval($_POST['quantite']);
    $libelle = htmlspecialchars($_POST['libelle']);
    $imagePath = null;

    // Gestion de l'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../uploads/produits/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $imagePath = $uploadDir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    // Mise à jour du produit
    $sql = "UPDATE produits SET nom_produit = ?, description = ?, prix = ?, quantite_disponible = ?, libelle = ?";
    $params = [$nom, $description, $prix, $quantite, $libelle];

    if ($imagePath) {
        $sql .= ", image = ?";
        $params[] = $imagePath;
    }

    $sql .= " WHERE id_produit = ?";
    $params[] = $id_produit;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    echo "<div class='notification is-success'>Produit modifié avec succès.</div>";
}

// Récupérer les statistiques
$nbInscrits = $pdo->query("SELECT COUNT(*) AS total FROM utilisateurs")->fetchColumn();
$nbCommandes = $pdo->query("SELECT COUNT(*) AS total FROM commandes")->fetchColumn();
$nbProduits = $pdo->query("SELECT COUNT(*) AS total FROM produits")->fetchColumn();
$nbRecettes = $pdo->query("SELECT COUNT(*) AS total FROM recettes")->fetchColumn();
$nbAvis = $pdo->query("SELECT COUNT(*) AS total FROM avis")->fetchColumn();
$nbCommandesEnCours = $pdo->query("SELECT COUNT(*) AS total FROM commandes WHERE statut_commande = 'en attente'")->fetchColumn();

// Récupérer les 5 produits les plus consultés
$produitsPlusVus = $pdo->query("SELECT * FROM produits ORDER BY vues DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les 5 recettes les plus consultées
$recettesPlusVues = $pdo->query("SELECT * FROM recettes ORDER BY vues DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

$autoloadPath = realpath('../../../vendor/autoload.php');
if (!$autoloadPath) {
    die('Le fichier autoload.php est introuvable. Chemin vérifié : ' . realpath('../../vendor/'));
}
require $autoloadPath;

if (isset($_POST['valider_commande'])) {
    $id_commande = (int)$_POST['id_commande'];

    // Mettre à jour le statut de la commande
    $stmt = $pdo->prepare("UPDATE commandes SET statut_commande = 'validée' WHERE id_commande = ?");
    $stmt->execute([$id_commande]);

    // Récupérer les informations de l'utilisateur
    $stmt = $pdo->prepare("
        SELECT u.email, u.prenom, u.nom, c.total 
        FROM commandes c
        JOIN utilisateurs u ON c.id_utilisateur = u.id_utilisateur
        WHERE c.id_commande = ?
    ");
    $stmt->execute([$id_commande]);
    $commande = $stmt->fetch();

    if ($commande) {
        $user_email = $commande['email'];
        $user_name = $commande['prenom'] . ' ' . $commande['nom'];
        $total = number_format($commande['total'], 2, ',', ' ');

        // Récupérer les détails des produits de la commande
        $stmt = $pdo->prepare("
            SELECT p.nom_produit, p.prix, dc.quantite 
            FROM details_commande dc
            JOIN produits p ON dc.id_produit = p.id_produit
            WHERE dc.id_commande = ?
        ");
        $stmt->execute([$id_commande]);
        $produits = $stmt->fetchAll();

        

        $produitsDetails = "<ul>";
        foreach ($produits as $produit) {
            $produitsDetails .= "<li>" . htmlspecialchars($produit['nom_produit']) . " - " . htmlspecialchars($produit['quantite']) . " x " . number_format($produit['prix'], 2, ',', ' ') . " €</li>";
        }
        $produitsDetails .= "</ul>";

        // Envoyer un email à l'utilisateur
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
            $mail->Subject = 'Votre commande a été validée';
            $mail->Body = "Bonjour $user_name,<br><br>Votre commande (n°CMD-" . str_pad($id_commande, 6, '0', STR_PAD_LEFT) . ") a été validée avec succès. Le montant total est de $total €.<br><br>Voici les détails de votre commande :<br>$produitsDetails<br>Merci pour votre confiance.";

            $mail->send();
        } catch (Exception $e) {
            echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
        }
    }
    // Débloquer un utilisateur
    if (isset($_POST['debloquer_utilisateur'])) {
        $id_utilisateur = (int)$_POST['id_utilisateur'];
        $stmt = $pdo->prepare("UPDATE utilisateurs SET bloque = 0, tentatives = 0 WHERE id_utilisateur = :id");
        $stmt->execute([':id' => $id_utilisateur]);
        echo "<div class='notification is-success'>Utilisateur débloqué avec succès.</div>";
    }
}
?>
<?php include '../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    
</head>
<body>


<main class="container">
    <br></br>
    <h1 class="title">Page de Gestion Administrateur</h1>


    <section class="section">
        <h2 class="title is-4">Statistiques</h2>
        <div class="columns is-multiline">
            <div class="column is-one-third">
                <div class="box has-text-centered">
                    <p class="title"><?= htmlspecialchars($nbInscrits); ?></p>
                    <p class="subtitle">Utilisateurs inscrits</p>
                </div>
            </div>
            <div class="column is-one-third">
                <div class="box has-text-centered">
                    <p class="title"><?= htmlspecialchars($nbCommandes); ?></p>
                    <p class="subtitle">Commandes totales</p>
                </div>
            </div>
            <div class="column is-one-third">
                <div class="box has-text-centered">
                    <p class="title"><?= htmlspecialchars($nbProduits); ?></p>
                    <p class="subtitle">Produits disponibles</p>
                </div>
            </div>
            <div class="column is-one-third">
                <div class="box has-text-centered">
                    <p class="title"><?= htmlspecialchars($nbRecettes); ?></p>
                    <p class="subtitle">Recettes publiées</p>
                </div>
            </div>
            <div class="column is-one-third">
                <div class="box has-text-centered">
                    <p class="title"><?= htmlspecialchars($nbAvis); ?></p>
                    <p class="subtitle">Avis publiés</p>
                </div>
            </div>
            <div class="column is-one-third">
                <div class="box has-text-centered">
                    <p class="title"><?= htmlspecialchars($nbCommandesEnCours); ?></p>
                    <p class="subtitle">Commandes en cours</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Message -->
    <?php if (!empty($message)): ?>
        <div class="notification is-success">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    <!-- Section Validation des Commandes -->
    <section class="section">
        <h2 class="title is-4">Validation des Commandes</h2>
        <?php
        $commandes = $pdo->query("SELECT * FROM commandes WHERE statut_commande = 'en attente'")->fetchAll();
        ?>
        <?php if (!empty($commandes)): ?>
            <table class="table is-striped is-fullwidth">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Utilisateur</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commandes as $commande): ?>
                        <tr>
                            <td><?= htmlspecialchars($commande['id_commande']); ?></td>
                            <td><?= htmlspecialchars($commande['id_utilisateur']); ?></td>
                            <td><?= number_format($commande['total'], 2); ?> €</td>
                            <td><?= htmlspecialchars($commande['date_commande']); ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="id_commande" value="<?= $commande['id_commande']; ?>">
                                    <button type="submit" name="valider_commande" class="button is-success">Valider</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune commande en attente de validation.</p>
        <?php endif; ?>
    </section>
    <section class="section">
        <h2 class="title is-4">Produits et Recettes les Plus Consultés</h2>
        <p class="subtitle">Affichez les produits et recettes les plus consultés.</p>
        <div class="columns is-vcentered">
            <!-- Bouton Produits les Plus Consultés -->
            <div class="column is-half">
                <button class="button is-primary is-fullwidth" onclick="toggleSection('produits-section')">
                    Produits les Plus Consultés
                </button>
            </div>

            <!-- Bouton Recettes les Plus Consultées -->
            <div class="column is-half has-text-right">
                <button class="button is-link is-fullwidth" onclick="toggleSection('recettes-section')">
                    Recettes les Plus Consultées
                </button>
            </div>
        </div>

        <!-- Section Produits -->
        <div id="produits-section" class="content is-hidden">
            <div class="box">
                <ul>
                    <?php foreach ($produitsPlusVus as $produit): ?>
                        <li>
                            <strong><?= htmlspecialchars($produit['nom_produit']); ?></strong> - <?= htmlspecialchars($produit['vues']); ?> vues
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Section Recettes -->
        <div id="recettes-section" class="content is-hidden">
            <div class="box">
                <ul>
                    <?php foreach ($recettesPlusVues as $recette): ?>
                        <li>
                            <strong><?= htmlspecialchars($recette['titre']); ?></strong> - <?= htmlspecialchars($recette['vues']); ?> vues
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </section>
    </section>
    <section class="section">
        <h2 class="title is-4">Gérer les produits de la boutique</h2>
        <p class="subtitle">Ajoutez, modifiez ou supprimez des produits.</p>
        <div class="columns is-vcentered">
            <!-- Bouton Liste des produits -->
            <div class="column is-half">
                <button class="button is-link is-fullwidth" onclick="toggleSection('product-list-section')">
                    Liste des produits
                </button>
            </div>

            <!-- Bouton Ajouter un produit -->
            <div class="column is-half has-text-right">
                <button class="button is-primary is-fullwidth" onclick="toggleSection('add-product-section')">
                    Ajouter un produit
                </button>
            </div>
        </div>

        <!-- Section Ajouter un produit -->
        <div id="add-product-section" class="content is-hidden">
            <form method="POST" enctype="multipart/form-data" class="box">
                <h2 class="title is-4">Ajouter un produit</h2>
                <!-- Champs du formulaire -->
                <div class="field">
                    <label class="label">🥤Nom du produit</label>
                    <div class="control">
                        <input class="input" type="text" name="nom" placeholder="Nom du produit" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">🏷️ Libellé</label>
                    <div class="control">
                        <input class="input" type="text" name="libelle" placeholder="Libellé du produit" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">📝  Description</label>
                    <div class="control">
                        <textarea class="textarea" name="description" placeholder="Description du produit" required></textarea>
                    </div>
                </div>
                <div class="field">
                    <label class="label">💶 Prix</label>
                    <div class="control">
                        <input class="input" type="number" step="0.01" name="prix" placeholder="Prix en €" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">📦 Quantité disponible</label>
                    <div class="control">
                        <input class="input" type="number" name="quantite" placeholder="Quantité disponible" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Image du produit</label>
                    <div class="control">
                        <input class="input" type="file" name="image" accept="image/*" required>
                    </div>
                </div>
                <div class="control">
                    <button class="button is-primary" type="submit" name="add_product">Ajouter le produit</button>
                </div>
            </form>
        </div>

        <!-- Section Liste des produits -->
        <?php
        // Récupérer les produits
        $produits = [];
        try {
                // Modifiez la requête pour utiliser une colonne existante si 'date_creation' n'existe pas
            $produits = $pdo->query("SELECT * FROM produits ORDER BY id_produit DESC")->fetchAll(PDO::FETCH_ASSOC);        } catch (Exception $e) {
            echo "Erreur lors de la récupération des produits : " . $e->getMessage();
        }?>
        <div id="product-list-section" class="content is-hidden">
            <h2 class="title is-4">Liste des produits</h2>
            <table class="table is-striped is-fullwidth">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Libellé</th>
                        <th>Description</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produits as $produit): ?>
                        <tr>
                            <td>
                                <?php if (!empty($produit['image'])): ?>
                                    <img src="../../<?= htmlspecialchars($produit['image'] ?? ''); ?>" style="max-width: 100px;">
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($produit['nom_produit'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($produit['libelle'] ?? ''); ?></td>
                            <td><?= htmlspecialchars(substr($produit['description'] ?? '', 0, 50)); ?>...</td>
                            <td><?= number_format($produit['prix'] ?? 0, 2); ?> €</td>
                            <td><?= htmlspecialchars($produit['quantite_disponible'] ?? '0'); ?></td>
                            <td>
                                <!-- Supprimer -->
                                <form method="POST" style="display:inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                                    <input type="hidden" name="id_produit" value="<?= htmlspecialchars($produit['id_produit'] ?? ''); ?>">
                                    <button type="submit" name="delete_product" class="button is-danger is-small">Supprimer</button>
                                </form>
                                <!-- Modifier -->
                                <button class="button is-link is-small" 
                                        onclick="openEditModalProduit(<?= htmlspecialchars(json_encode($produit)); ?>)">
                                    Modifier
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
    <h1 class="title mt-5">Page de Gestion Site</h1>
    <!-- Section Gestion Produits -->
    <?php if ($isSuperAdmin): ?>
        <section class="section">
            <h2 class="title is-4">Créer un administrateur</h2>
            <form method="POST">
                <div class="field">
                    <label class="label">Nom</label>
                    <input class="input" type="text" name="nom" placeholder="Nom" required>
                </div>
                <div class="field">
                    <label class="label">Prénom</label>
                    <input class="input" type="text" name="prenom" placeholder="Prénom" required>
                </div>
                <div class="field">
                    <label class="label">Email</label>
                    <input class="input" type="email" name="email" placeholder="Email" required>
                </div>
                <div class="field">
                    <label class="label">Mot de passe</label>
                    <input class="input" type="password" name="mot_de_passe" placeholder="Mot de passe" required>
                </div>
                <div class="control">
                    <button type="submit" name="create_admin" class="button is-primary">Créer un administrateur</button>
                </div>
            </form>
        </section>
    <?php endif; ?>
    <!-- Section Gestion Utilisateurs -->
    <section class="section">
        <h2 class="title is-4">Gestion des utilisateurs</h2>
        <table class="table is-striped is-fullwidth">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Date de création</th>
                    <th>Statut</th>
                    <th>Supendu</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($utilisateurs as $utilisateur): ?>
                <tr>
                <td><?= htmlspecialchars($utilisateur['id_utilisateur']); ?></td>
                    <td>
                    <?php if (!empty($utilisateur['photo_profil']) && file_exists("../../" . $utilisateur['photo_profil'])): ?>
                        <img src="../../<?= htmlspecialchars($utilisateur['photo_profil']); ?>" alt="Photo de profil" style="max-width: 50px; max-height: 150px; border-radius: 25%;">
                    <?php else: ?>
                        <img src="../../uploads/profils/default.png" alt="Photo de profil par défaut" style="max-width: 50px; max-height: 150px; border-radius: 25%;">
                    <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($utilisateur['nom']); ?></td>
                    <td><?= htmlspecialchars($utilisateur['prenom']); ?></td>
                    <td><?= htmlspecialchars($utilisateur['email']); ?></td>
                    <td><?= htmlspecialchars($utilisateur['role']); ?></td>
                    <td><?= htmlspecialchars($utilisateur['date_creation']); ?></td>
                    <td><?= $utilisateur['bloque'] ? 'Bloqué' : 'Actif'; ?></td>
                    <td>
                        <?php if ($utilisateur['bloque']): ?>
                            <form method="POST">
                                <input type="hidden" name="id_utilisateur" value="<?= $utilisateur['id_utilisateur']; ?>">
                                <button type="submit" name="debloquer_utilisateur" class="button is-warning">Débloquer</button>
                            </form>
                        <?php endif; ?>
                    </td>
                    <td>
                        <!-- Bouton Modifier -->
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="id_utilisateur" value="<?= $utilisateur['id_utilisateur']; ?>">
                            <button type="button" class="button is-link is-small" onclick="openEditModalUser(<?= htmlspecialchars(json_encode($utilisateur)); ?>)">Modifier</button>
                        </form>

                        <!-- Bouton Supprimer -->
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="id_utilisateur" value="<?= $utilisateur['id_utilisateur']; ?>">
                            <button type="submit" name="delete_user" class="button is-danger is-small" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Section Gestion Avis -->
    <section class="section">
        <h2 class="title is-4">Gestion des avis</h2>
        <table class="table is-striped is-fullwidth">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Note</th>
                    <th>Commentaire</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($avis as $avis_item): ?>
                    <tr>
                        <td><?= htmlspecialchars($avis_item['id_avis']); ?></td>
                        <td><?= htmlspecialchars($avis_item['prenom_utilisateur'] . ' ' . $avis_item['nom_utilisateur']); ?></td>
                        <td><?= htmlspecialchars($avis_item['note']); ?>/5</td>
                        <td><?= htmlspecialchars(substr($avis_item['commentaire'], 0, 50)) . '...'; ?></td>
                        <td><?= htmlspecialchars($avis_item['type_contenu']); ?></td>
                        <td>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="id_avis" value="<?= $avis_item['id_avis']; ?>">
                                <button type="submit" name="delete_avis" class="button is-danger is-small">Supprimer</button>
                            </form>
                            <button class="button is-link is-small" onclick="openEditModalAvis(<?= htmlspecialchars(json_encode($avis_item)); ?>)">Modifier</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Section Gestion Recettes -->
    <section class="section">
        <h2 class="title is-4">Gestion des recettes</h2>
        <table class="table is-striped is-fullwidth">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recettes as $recette): ?>
                    <tr>
                        <td><?= htmlspecialchars($recette['id_recette']); ?></td>
                        <td><?= htmlspecialchars($recette['prenom_utilisateur'] . ' ' . $recette['nom_utilisateur']); ?></td>
                        <td><?= htmlspecialchars($recette['titre']); ?></td>
                        <td><?= htmlspecialchars($recette['categorie']); ?></td>
                        <td>
                            <!-- Bouton Modifier Recette -->
                            <form method="POST" enctype="multipart/form-data" style="display: inline;">
                                <input type="hidden" name="id_recette" value="<?= $recette['id_recette']; ?>">
                                <button type="button" class="button is-link is-small" onclick="openEditModalRecette(<?= htmlspecialchars(json_encode($recette)); ?>)">Modifier</button>
                            </form>
                            <!-- Bouton Supprimer Recette -->
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="id_recette" value="<?= $recette['id_recette']; ?>">
                                <button type="submit" name="delete_recette" class="button is-danger is-small">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include '../includes/footer.php'; ?>

<script>
    // Fonction pour afficher le modal de modification des utilisateurs
function openEditModalUser(user) {
    // Vérifier si un modal existe déjà et le supprimer
    const existingModal = document.getElementById("editUserModal");
    if (existingModal) {
        existingModal.remove();
    }

    // Créer le contenu du modal
    const modalContent = `
        <div id="editUserModal" class="modal is-active">
            <div class="modal-background" onclick="closeModal('editUserModal')"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Modifier l'utilisateur</p>
                    <button class="delete" aria-label="close" onclick="closeModal('editUserModal')"></button>
                </header>
                <form method="POST">
                    <section class="modal-card-body">
                        <input type="hidden" name="id_utilisateur" value="${user.id_utilisateur}">
                        <div class="field">
                            <label class="label">Nom</label>
                            <div class="control">
                                <input class="input" type="text" name="nom" value="${user.nom}" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Prénom</label>
                            <div class="control">
                                <input class="input" type="text" name="prenom" value="${user.prenom}" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Email</label>
                            <div class="control">
                                <input class="input" type="email" name="email" value="${user.email}" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Rôle</label>
                            <div class="control">
                                <div class="select">
                                    <select name="role" required>
                                        <option value="utilisateur" ${user.role === 'utilisateur' ? 'selected' : ''}>Utilisateur</option>
                                        <option value="administrateur" ${user.role === 'administrateur' ? 'selected' : ''}>Administrateur</option>
                                        <option value="super_administrateur" ${user.role === 'super_administrateur' ? 'selected' : ''}>Super Administrateur</option>
                                        <option value="coach" <?= $utilisateur['role'] === 'coach' ? 'selected' : ''; ?>>Coach</option>
                                        <option value="commercial" <?= $utilisateur['role'] === 'commercial' ? 'selected' : ''; ?>>Commercial</option>         
                                    </select>
                                </div>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <button type="submit" name="edit_user" class="button is-success">Enregistrer</button>
                        <button type="button" class="button" onclick="closeModal('editUserModal')">Annuler</button>
                    </footer>
                </form>
            </div>
        </div>
    `;

    // Insérer le modal dans le DOM
    document.body.insertAdjacentHTML("beforeend", modalContent);
}

// Fonction pour fermer le modal
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.remove();
    }
}


// Fonction pour afficher le modal de modification des avis
function openEditModalAvis(avis) {
    const modalContent = `
        <div class="modal is-active" id="editAvisModal">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Modifier Avis</p>
                    <button class="delete" aria-label="close" onclick="closeModal('editAvisModal')"></button>
                </header>
                <form method="POST">
                    <section class="modal-card-body">
                        <input type="hidden" name="id_avis" value="${avis.id_avis}">
                        <div class="field">
                            <label class="label">Commentaire</label>
                            <div class="control">
                                <textarea class="textarea" name="commentaire" required>${avis.commentaire}</textarea>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Note</label>
                            <div class="control">
                                <input class="input" type="number" name="note" min="1" max="5" value="${avis.note}" required>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <button type="submit" name="edit_avis" class="button is-success">Modifier</button>
                        <button type="button" class="button" onclick="closeModal('editAvisModal')">Annuler</button>
                    </footer>
                </form>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML("beforeend", modalContent);
}

// Fonction pour afficher le modal de modification des recettes
function openEditModalRecette(recette) {
    // Supprimer un modal existant s'il est déjà ouvert
    const existingModal = document.getElementById("editRecetteModal");
    if (existingModal) existingModal.remove();

    // Contenu du modal
    const modalContent = `
        <div class="modal is-active" id="editRecetteModal">
            <div class="modal-background" onclick="closeModal('editRecetteModal')"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Modifier la recette</p>
                    <button class="delete" aria-label="close" onclick="closeModal('editRecetteModal')"></button>
                </header>
                <form method="POST" enctype="multipart/form-data">
                    <section class="modal-card-body">
                        <input type="hidden" name="id_recette" value="${recette.id_recette}">
                        <div class="field">
                            <label class="label">Titre</label>
                            <div class="control">
                                <input class="input" type="text" name="titre" value="${recette.titre}" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Description</label>
                            <div class="control">
                                <textarea class="textarea" name="description" required>${recette.description}</textarea>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Catégorie</label>
                            <div class="control">
                                <input class="input" type="text" name="categorie" value="${recette.categorie}" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Ingrédients</label>
                            <div class="control">
                                <textarea class="textarea" name="ingredients" required>${recette.ingredients}</textarea>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Étapes</label>
                            <div class="control">
                                <textarea class="textarea" name="etapes" required>${recette.etapes}</textarea>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Image</label>
                            <div class="control">
                                <input class="input" type="file" name="image" accept="image/*">
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <button type="submit" name="edit_recette" class="button is-success">Enregistrer</button>
                        <button type="button" class="button" onclick="closeModal('editRecetteModal')">Annuler</button>
                    </footer>
                </form>
            </div>
        </div>
    `;

    // Insérer le modal dans le DOM
    document.body.insertAdjacentHTML("beforeend", modalContent);
}


function openEditModalProduit(produit) {
    // Supprimer un modal existant s'il est déjà ouvert
    const existingModal = document.getElementById("editProductModal");
    if (existingModal) existingModal.remove();

    // Contenu du modal
    const modalContent = `
        <div class="modal is-active" id="editProductModal">
            <div class="modal-background" onclick="closeModal('editProductModal')"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Modifier Produit</p>
                    <button class="delete" aria-label="close" onclick="closeModal('editProductModal')"></button>
                </header>
                <form method="POST" enctype="multipart/form-data">
                    <section class="modal-card-body">
                        <input type="hidden" name="id_produit" value="${produit.id_produit}">
                        <div class="columns">
                            <!-- Colonne pour l'image -->
                            <div class="column is-one-third has-text-centered">
                                <figure class="image is-128x128 is-inline-block">
                                    <img id="productImagePreview" src="../../${produit.image}" alt="Aperçu de l'image" style="max-width: 100%; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 10px;">
                                </figure>
                                <div class="field mt-3">
                                    <label class="label">Changer l'image</label>
                                    <div class="control">
                                        <input class="input" type="file" name="image" accept="image/*" onchange="previewImage(event, 'productImagePreview')">
                                    </div>
                                </div>
                            </div>
                            <!-- Colonne pour les champs -->
                            <div class="column">
                                <div class="field">
                                    <label class="label">Nom</label>
                                    <div class="control">
                                        <input class="input" type="text" name="nom" value="${produit.nom_produit}" required>
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="label">Libellé</label>
                                    <div class="control">
                                        <input class="input" type="text" name="libelle" value="${produit.libelle || ''}" required>
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="label">Description</label>
                                    <div class="control">
                                        <textarea class="textarea" name="description" required>${produit.description}</textarea>
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="label">Prix</label>
                                    <div class="control">
                                        <input class="input" type="number" step="0.01" name="prix" value="${produit.prix}" required>
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="label">Quantité</label>
                                    <div class="control">
                                        <input class="input" type="number" name="quantite" value="${produit.quantite_disponible}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <button type="submit" name="edit_product" class="button is-success">Modifier</button>
                        <button type="button" class="button" onclick="closeModal('editProductModal')">Annuler</button>
                    </footer>
                </form>
            </div>
        </div>
    `;

    // Insérer le modal dans le DOM
    document.body.insertAdjacentHTML("beforeend", modalContent);

// Ajuste la hauteur après l'insertion
const modalCard = document.querySelector("#editProductModal .modal-card");
modalCard.style.maxHeight = "150vh";
}

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('productImagePreview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}


function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.remove();
}

function toggleSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section.classList.contains('is-hidden')) {
         section.classList.remove('is-hidden');
    } else {
        section.classList.add('is-hidden');
    }
}


</script>
</body>
</html>
