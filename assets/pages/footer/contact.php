<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Exemple d'envoi par email
    $to = "nathael.lebihan12102005@gmail.com";
    $subject = "Nouveau message de contact - $nom";
    $headers = "From: $email";

    if (mail($to, $subject, $message, $headers)) {
        $success = "Votre message a été envoyé avec succès !";
    } else {
        $error = "Une erreur s'est produite lors de l'envoi du message. Veuillez réessayer.";
    }
}
?>

<?php include '../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
<main class="container mt-5">
    <section class="section">
        <div class="container">
            <h1 class="title is-3 has-text-centered">Contactez-nous</h1>
            <p class="subtitle is-5 has-text-centered">
                Une question ? Un problème ? Remplissez le formulaire ci-dessous et nous vous répondrons rapidement.
            </p>
        </div>

        <div class="columns is-centered">
            <div class="column is-half">
                <?php if (isset($success)): ?>
                    <div class="notification is-success">
                        <?= $success; ?>
                    </div>
                <?php elseif (isset($error)): ?>
                    <div class="notification is-danger">
                        <?= $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="field">
                        <label class="label" for="nom">Nom</label>
                        <div class="control">
                            <input class="input" type="text" id="nom" name="nom" placeholder="Votre nom" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label" for="email">Email</label>
                        <div class="control">
                            <input class="input" type="email" id="email" name="email" placeholder="Votre email" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label" for="message">Message</label>
                        <div class="control">
                            <textarea class="textarea" id="message" name="message" placeholder="Votre message" rows="5" required></textarea>
                        </div>
                    </div>

                    <div class="field is-grouped is-justify-content-center">
                        <div class="control">
                            <button type="submit" class="button is-primary">Envoyer</button>
                        </div>
                        <div class="control">
                            <button type="reset" class="button is-light">Réinitialiser</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>