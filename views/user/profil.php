<?php
if (!isset($_GET['u']) || empty($_GET['u'])) {
    die("Aucun profil spécifié.");
}

$username = $_GET['u'];

$stmt = $pdo->prepare("SELECT id, username FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user) {
    http_response_code(404);
    die("Utilisateur introuvable.");
}

$stmt2 = $pdo->prepare("SELECT * FROM profiles WHERE user_id = ?");
$stmt2->execute([$user['id']]);
$userProfile = $stmt2->fetch();

if (!$userProfile) {
    die("Ce profil n'est pas encore configuré.");
}

$stmt3 = $pdo->prepare("SELECT * FROM links WHERE profile_id = ? ORDER BY position ASC"); // Ajout d'un ORDER BY c'est mieux
$stmt3->execute([$userProfile['id']]);
$links = $stmt3->fetchAll();

// Définition de l'avatar (avec gestion d'erreur si vide)
$avatarSrc = !empty($userProfile['avatar_path']) ? $userProfile['avatar_path'] : 'https://via.placeholder.com/150';
// Si tu as une constante BASE_URL définie ailleurs, assure-toi de l'inclure, sinon retire "BASE_URL ." ci-dessous
$fullAvatarPath = defined('BASE_URL') ? BASE_URL . $avatarSrc : $avatarSrc;

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= htmlspecialchars($userProfile['display_name']) ?> | Mes Liens
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* On applique la couleur de fond choisie par l'utilisateur */
        body {
            background-color:
                <?= htmlspecialchars($userProfile['theme_color_bg']) ?>
            ;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 50px;
            font-family: sans-serif;
        }

        .profile-container {
            width: 100%;
            max-width: 680px;
            /* Largeur type mobile/tablette */
            padding: 20px;
            text-align: center;
        }

        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        /* Couleur du texte choisie par l'utilisateur */
        .profile-name,
        .profile-bio {
            color:
                <?= htmlspecialchars($userProfile['theme_color_font']) ?>
            ;
        }

        .profile-name {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .profile-bio {
            font-size: 1rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        /* Style des boutons liens */
        .link-btn {
            display: block;
            width: 100%;
            padding: 16px 20px;
            margin-bottom: 16px;
            border-radius: 50px;
            /* Boutons arrondis style Linktree */
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s, opacity 0.2s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);

            /* Couleurs dynamiques */
            background-color:
                <?= htmlspecialchars($userProfile['theme_color_btn']) ?>
            ;
            /* Pour le texte du bouton, on peut soit ajouter une option en BDD, 
               soit mettre blanc/noir par défaut. Ici je mets blanc pour l'exemple */
            color: #ffffff;
            border: 2px solid transparent;
        }

        .link-btn:hover {
            transform: scale(1.02);
            opacity: 0.9;
        }
    </style>
</head>

<body>

    <div class="profile-container">

        <img src="<?= htmlspecialchars($fullAvatarPath) ?>" alt="<?= htmlspecialchars($userProfile['display_name']) ?>"
            class="avatar">

        <h1 class="profile-name">
            <?= htmlspecialchars($userProfile['display_name']) ?>
        </h1>
        <p class="profile-bio">
            <?= nl2br(htmlspecialchars($userProfile['bio'])) ?>
        </p>

        <div class="links-wrapper">
            <?php if (count($links) > 0): ?>
                <?php foreach ($links as $link): ?>
                    <?php if ($link['is_active']): ?>
                        <a href="<?= htmlspecialchars($link['url']) ?>" target="_blank" class="link-btn">
                            <?= htmlspecialchars($link['title']) ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #fff;">Aucun lien pour le moment.</p>
            <?php endif; ?>
        </div>

    </div>

    <footer style="margin-top: auto; padding-bottom: 20px; opacity: 0.6;">
        <a href="index.php" style="color: inherit; text-decoration: none; font-size: 0.8rem;">
            Créé avec MyLink-In-Bio
        </a>
    </footer>

</body>

</html>