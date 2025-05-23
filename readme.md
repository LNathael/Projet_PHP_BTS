## **Documentation Technique**

### **1. Introduction**

* **Nom du projet :** NutriStrong
* **Description :** Une plateforme web multifonctionnelle pour [objectif principal, ex. : gestion des utilisateurs, e-commerce, etc.].
* **Technologies utilisées :**
  * **Frontend :** HTML, CSS (Bulma), JavaScript.
  * **Backend :** PHP (avec PDO pour les interactions avec la base de données).
  * **Base de données :** MySQL.
  * **Hébergement local :** Laragon.

---

### **2. Structure du projet**

#### **2.1 Arborescence des fichiers**

Projet_PHP_BT/
├── assets/
│   ├── css/                # Fichiers CSS personnalisés
│   │   └── style.css       # Fichier principal de styles
│   ├── js/                 # Scripts JavaScript
│   │   └── script.js       # Fichier principal de scripts
│   ├── pages/              # Pages principales du projet
│   │   ├── Acceuil/              # Pages principales du projet
│   │   │   ├── accueil.php           # Page de connexion
│   │   │   └── index.php               # Page d'accueil principale
│   │   ├── admin/          # Pages pour les administrateurs
│   │   │   ├── gestion_admin.php       # Gestion des administrateurs
│   │   │   ├── gestion_produits.php    # Gestion des produits
│   │   │   └── gestion_commandes.php   # Gestion des commandes
│   │   ├── Salon/          # Pages pour les salons de discussion
│   │   │   ├── chat.php                # Chat des salons
│   │   │   └── liste_salons.php        # Liste des salons
│   │   ├── Connexion/      # Pages pour l'inscription et la connexion
│   │   │   ├── connexion.php           # Page de connexion
│   │   │   ├── inscription.php         # Page d'inscription
│   │   │   └── compte.php              # Gestion du compte utilisateur
│   │   ├── recettes/       # Pages pour les recettes
│   │   │   ├── ajouter_recette.php     # Ajouter une recette
│   │   │   ├── liste_recettes.php      # Liste des recettes
│   │   │   └── detail_recette.php      # Détail d'une recette
│   │   ├── produits/       # Pages pour les produits
│   │   │   ├── liste_produits.php      # Liste des produits
│   │   │   └── detail_produit.php      # Détail d'un produit
│   │   ├── Programme/      # Pages pour les programmes personnalisés
│   │   │   ├── afficher_programmes.php # Afficher les programmes
│   │   │   ├── detail_programme.php    # Détail d'un programme
│   │   │   ├── programmes_personnalises.php # Création de programmes personnalisés
│   │   │   └── programmes_debutants.php     # Programme pour débutants
│   │   └── Panier/         # Pages pour la gestion du panier
│   │       ├── panier.php              # Gestion du panier
│   │       ├── validation_commande.php # Validation des commandes
│   │       └── detail_commande.php     # Détail d'une commande
├── config/
│   └── db.php              # Configuration de la base de données
├── includes/
│   ├── header.php          # En-tête commun
│   ├── footer.php          # Pied de page commun
│   └── session_start.php   # Gestion des sessions
├── uploads/                # Dossier pour les fichiers téléchargés (images, etc.)
│   ├── produits/           # Images des produits
│   ├── recettes/           # Images des recettes
│   └── programmes/         # Fichiers liés aux programmes
├── sql/                    # Scripts SQL pour la base de données
│   ├── schema.sql          # Schéma de la base de données
│   ├── sql_reparation.sql  # Script de réparation
│   ├── sql_base_bonne_DB.sql # Base de données initiale
│   └── fichier.sql         # Autres scripts SQL

└── readme.md               # Documentation du projet

---

### **3. Configuration**

#### **3.1 Base de données**

* **Nom de la base de données :** `projet_php_bts`
* **Tables principales :**
  * `utilisateurs` : Contient les informations des utilisateurs (nom, prénom, email, rôle, etc.).
  * `produits` : Contient les informations des produits (nom, description, prix, etc.).
  * `recettes` : Contient les informations des recettes (titre, description, ingrédients, etc.).
  * `messages` : Contient les messages des salons de discussion.
  * `salons` : Contient les informations des salons de discussion.
  * `commandes` : Contient les informations des commandes.

#### **3.2 Configuration de la base de données**

Le fichier **`config/db.php`** contient les informations de connexion à la base de données :

```
<?php
$host = '127.0.0.1';
$dbname = 'projet_php_bts';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
```

### **4. Fonctionnalités principales**

---

### ** Comment lancer le projet**

#### Prérequis**

-**Serveur local :** Installez Laragon, XAMPP ou WAMP.

-**Base de données :** MySQL (inclus dans Laragon, XAMPP ou WAMP).

-**Navigateur web :** Google Chrome, Firefox ou tout autre navigateur moderne.

#### **Étapes pour lancer le projet**

1.**Cloner le projet :**

- Téléchargez ou clonez le projet dans le répertoire racine de votre serveur local (par exemple, `C:/laragon/www/` pour Laragon).

2.**Importer la base de données :**

- Ouvrez **phpMyAdmin** ou tout autre outil de gestion MySQL.
- Créez une base de données nommée `projet_php_bts`.
- Importez le fichier SQL initial : `sql/sql_base_bonne_DB.sql`.

3.**Configurer la connexion à la base de données :**

- Ouvrez le fichier `config/db.php`.
- Vérifiez que les paramètres (`$host`, `$dbname`, `$username`, `$password`) correspondent à votre configuration locale.

4.**Lancer le serveur local :**

- Démarrez Laragon, XAMPP ou WAMP.
- Accédez à l'URL suivante dans votre navigateur : `http://localhost/Projet_PHP_BT/`.

5.**Tester le projet :**

- Connectez-vous avec un compte utilisateur ou administrateur (si des comptes de test sont inclus dans la base de données).
  - compte SuperAdmin : alice.dupont@superadmin.com || MDP : password123
- - compte admin ( compte remplie par des donnes ): xarunax69@gmail.com  || MDP : Xarunax1216__#
  - Compte utilisateur : vous pouvez le crée par la page [inscription.php]()
- Explorez les fonctionnalités (connexion, gestion des produits, commandes, etc.).

---

#### **4.1 Gestion des utilisateurs**

* **Fichiers concernés :**
  * [gestion_admin.php](vscode-file://vscode-app/c:/Users/xarun/AppData/Local/Programs/Microsoft%20VS%20Code/resources/app/out/vs/code/electron-sandbox/workbench/workbench.html)
  * [connexion.php](vscode-file://vscode-app/c:/Users/xarun/AppData/Local/Programs/Microsoft%20VS%20Code/resources/app/out/vs/code/electron-sandbox/workbench/workbench.html)
  * [details_utilisateur.php](vscode-file://vscode-app/c:/Users/xarun/AppData/Local/Programs/Microsoft%20VS%20Code/resources/app/out/vs/code/electron-sandbox/workbench/workbench.html)
* **Description :**
  * Inscription, connexion, gestion des profils.
  * Attribution de rôles (`utilisateur`, `administrateur`, `coach`, etc.).
  * Blocage et déblocage des utilisateurs.

#### **4.2 Gestion des produits**

* **Fichiers concernés :**
  * `assets/pages/produits/gestion_produits.php`
  * `assets/pages/produits/detail_produit.php`
* **Description :**
  * Ajout, modification et suppression des produits.
  * Affichage des produits disponibles.

#### **4.3 Gestion des recettes**

* **Fichiers concernés :**
  * `assets/pages/recettes/gestion_recettes.php`
  * `assets/pages/recettes/detail_recette.php`
* **Description :**
  * Ajout, modification et suppression des recettes.
  * Consultation des recettes par les utilisateurs.

#### **4.4 Chat et salons de discussion**

* **Fichiers concernés :**
  * [chat.php](vscode-file://vscode-app/c:/Users/xarun/AppData/Local/Programs/Microsoft%20VS%20Code/resources/app/out/vs/code/electron-sandbox/workbench/workbench.html)
  * [details_utilisateur.php](vscode-file://vscode-app/c:/Users/xarun/AppData/Local/Programs/Microsoft%20VS%20Code/resources/app/out/vs/code/electron-sandbox/workbench/workbench.html)
* **Description :**
  * Salons de discussion pour les utilisateurs.
  * Affichage des messages avec les informations des utilisateurs.

#### **4.5 Gestion des commandes**

* **Fichiers concernés :**
  * `assets/pages/admin/gestion_commandes.php`
* **Description :**
  * Validation des commandes par les administrateurs.
  * Suivi des statuts des commandes.

### **5. Sécurité**

#### **5.1 Gestion des mots de passe**

* Les mots de passe des utilisateurs sont hachés avec `password_hash()` :

  ```
  <?php
  $password_hashed = password_hash($password, PASSWORD_BCRYPT);
  ```
  #### **5.2 Protection contre les injections SQL**


  * 
  * Utilisation de requêtes préparées avec PDO :

    ```
    <?php
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
    $stmt->execute([':email' => $email]);
    ```
    #### **5.3 Gestion des sessions**


    * Les sessions sont utilisées pour authentifier les utilisateurs :

    ```
    <?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../Connexion/connexion.php');
        exit;
    }
    ```
    ### **6. Instructions pour les développeurs**

    #### **6.1 Ajout d'une nouvelle fonctionnalité**

    1. Créez un nouveau fichier dans le dossier approprié (ex. : `assets/pages/produits/`).
    2. Ajoutez la logique backend dans le fichier PHP.
    3. Ajoutez les styles nécessaires dans le fichier CSS.
    4. Testez la fonctionnalité localement.

    #### **6.2 Déploiement**

    1. Exportez la base de données MySQL avec les scripts SQL.
    2. Configurez le fichier `config/db.php` avec les informations du serveur de production.
    3. Testez toutes les fonctionnalités sur le serveur de production.

    ---

    ### **7. Maintenance**

    #### **7.1 Sauvegarde**

    * Sauvegardez régulièrement la base de données avec la commande suivante :
      `mysqldump -u root -p projet_php_bts > sauvegarde.sql`
    * 

    #### **7.2 Mise à jour**

    * Avant toute mise à jour, effectuez une sauvegarde complète des fichiers et de la base de données.
    * Testez les nouvelles fonctionnalités sur un environnement de développement avant de les déployer en production.

    ---

    ### **8. Contact**

    * **Développeur principal : Nathael Le Bihan**
    * **Email : nathael.lebihan12102005@gmail.com**
