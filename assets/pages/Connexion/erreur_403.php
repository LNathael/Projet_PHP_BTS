<?php
http_response_code(403); // Définit le code de réponse HTTP à 403
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès interdit</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <section class="section">
        <div class="container has-text-centered">
            <h1 class="title">403 - Accès interdit</h1>
            <p class="subtitle">Vous n'avez pas les permissions nécessaires pour accéder à cette page.</p>
            <a href="../Acceuil/accueil.php" class="button is-primary">Retour à l'accueil</a>
        </div>
    </section>
</body>
</html>