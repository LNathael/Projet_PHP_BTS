<?php
// Inclure le fichier header
include '../includes/header.php'; 
require_once '../config/db.php'; // Connexion à la base de données

// Vérifier si une requête de recherche est reçue
if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
    // Nettoyer et récupérer la requête
    $query = htmlspecialchars(trim($_GET['query']));

    echo "<main class='container'>";
    echo "<h1 class='title is-4'>Résultats de recherche pour : <span class='has-text-primary'>$query</span></h1>";

    // Rechercher dans la base de données
    $stmt = $pdo->prepare("
        SELECT 'produit' AS type, id_produit AS id, nom_produit AS titre, description AS contenu 
        FROM produits 
        WHERE nom_produit LIKE :query OR description LIKE :query
        UNION
        SELECT 'recette' AS type, id_recette AS id, titre AS titre, description AS contenu 
        FROM recettes 
        WHERE titre LIKE :query OR description LIKE :query
        UNION
        SELECT 'article' AS type, id_article AS id, titre AS titre, contenu AS contenu 
        FROM articles 
        WHERE titre LIKE :query OR contenu LIKE :query
    ");
    $stmt->execute(['query' => "%$query%"]);
    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ajouter les résultats simulés (pages statiques)
    $pages = [
        [
            "titre" => "Livraison et Retours",
            "contenu" => "Nous livrons dans toute la France sous 3 à 5 jours ouvrés. Les frais de livraison sont calculés en fonction du poids.",
            "lien" => "../footer/livraison_retours.php"
        ],
        [
            "titre" => "FAQ - Questions Fréquentes",
            "contenu" => "Nous acceptons les paiements par carte bancaire, PayPal et virement bancaire. Suivez votre commande avec un numéro de suivi.",
            "lien" => "../footer/faq.php"
        ],
        [
            "titre" => "Blog - Conseils Musculation",
            "contenu" => "Découvrez nos astuces pour la prise de masse, la perte de poids, et les routines adaptées à vos objectifs sportifs.",
            "lien" => "../footer/blog.php"
        ],
    ];

    foreach ($pages as $page) {
        if (stripos($page['contenu'], $query) !== false || stripos($page['titre'], $query) !== false) {
            $resultats[] = [
                'type' => 'page',
                'id' => null,
                'titre' => $page['titre'],
                'contenu' => $page['contenu'],
                'lien' => $page['lien']
            ];
        }
    }

    // Afficher les résultats trouvés
    if (!empty($resultats)) {
        echo "<ul class='content'>";
        foreach ($resultats as $resultat) {
            // Mettre en surbrillance le terme recherché dans les résultats
            $keywords = explode(' ', $query);
            $highlighted = $resultat['contenu'];
            foreach ($keywords as $word) {
                $highlighted = str_ireplace($word, "<span class='has-background-warning'>$word</span>", $highlighted);
            }

            // Générer le lien en fonction du type de contenu
            $lien = $resultat['type'] === 'page' ? $resultat['lien'] : "../{$resultat['type']}/detail_{$resultat['type']}.php?id={$resultat['id']}";

            echo "
                <li>
                    <a href='$lien' class='has-text-link'>
                        <strong>{$resultat['titre']}</strong>
                    </a>
                    <p>$highlighted</p>
                </li>
            ";
        }
        echo "</ul>";
    } else {
        // Aucun résultat trouvé
        echo "<p class='has-text-danger'>Aucun résultat trouvé pour le terme recherché.</p>";
    }
    echo "</main>";

} else {
    // Si aucune recherche valide n'a été effectuée
    echo "<main class='container'>";
    echo "<h1 class='title is-4'>Veuillez saisir un terme de recherche valide.</h1>";
    echo "</main>";
}

// Inclure le fichier footer
include '../includes/footer.php'; 
?>