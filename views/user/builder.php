<?php
/* 
===================================================
Sécurité : vérifier si l'utilisateur est connecté
===================================================
*/

if (!isset($_SESSION['user']['id'])) {
    header('Location: index.php?action=login');
    exit;
}

$userId = $_SESSION['user']['id'];

$stmt1 = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt1->execute([$userId]);
$user = $stmt1->fetch();

if ($user) {
    $stmt2 = $pdo->prepare("SELECT * FROM profiles WHERE user_id = ?");
    $stmt2->execute([$userId]);
    $userProfile = $stmt2->fetch();
}

if ($userProfile) {
    $stmt3 = $pdo->prepare('SELECT * FROM links WHERE profile_id = ?');
    $stmt3->execute([$userProfile['id']]);
    $userLinks = $stmt3->fetchAll();
}

/* 
===================================================
Formulaire Profil
===================================================
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit-profil'])) {
        $name = null;
        // Si un profil existe déjà, on conserve l'ancien chemin de l'avatar par défaut.
        // Il ne sera écrasé que si une nouvelle image est téléversée.
        if ($userProfile) {
            $name = $userProfile['avatar_path'];
        }
        $displayName = $_POST['display_name'];
        $bio = $_POST['bio'];
        $themeColorBg = $_POST['theme_color_bg'];
        $themeColorBtn = $_POST['theme_color_btn'];
        $themeColorFont = $_POST['theme_color_font'];

        if (isset($_FILES['avatar_path']) && $_FILES['avatar_path']['error'] === 0) {
            $temp = $_FILES['avatar_path']['tmp_name'];
            $path = ROOT . '/uploads/avatars/' . $_FILES['avatar_path']['name'];
            $name = 'uploads/avatars/' . $_FILES['avatar_path']['name'];
            $moved = move_uploaded_file($temp, $path);

            if ($moved) {
                echo 'image enregistrée';
            } else
                echo 'erreur du fichier';
        }

        if ($userProfile) {
            //Cas UPDATE => UserProfil existe => On met à jour les données
            $sql = "UPDATE profiles SET url_slug = :url_slug, display_name = :display_name, bio = :bio, avatar_path = :avatar_path, theme_color_bg = :theme_color_bg, theme_color_btn = :theme_color_btn, theme_color_font = :theme_color_font WHERE user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $request = $stmt->execute([
                ':url_slug' => $displayName,
                ':display_name' => $displayName,
                ':bio' => $bio,
                ':avatar_path' => $name,
                ':theme_color_bg' => $themeColorBg,
                ':theme_color_btn' => $themeColorBtn,
                ':theme_color_font' => $themeColorFont,
                ':user_id' => $userId
            ]);

            if ($request) {
                header('Location: index.php?action=builder');
                exit;
            }
        } else {
            //Cas INSERT => UserProfil n'existe pas => Premier remplissage
            $sql = "INSERT INTO profiles (user_id, url_slug, display_name, bio, avatar_path, theme_color_bg, theme_color_btn, theme_color_font) VALUES (:user_id, :url_slug, :display_name, :bio, :avatar_path, :theme_color_bg, :theme_color_btn, :theme_color_font)";
            $stmt = $pdo->prepare($sql);
            $request = $stmt->execute([
                ':user_id' => $user['id'],
                ':url_slug' => $displayName,
                ':display_name' => $displayName,
                ':bio' => $bio,
                ':avatar_path' => $name,
                ':theme_color_bg' => $themeColorBg,
                ':theme_color_btn' => $themeColorBtn,
                ':theme_color_font' => $themeColorFont
            ]);

            if ($request) {
                header('Location: index.php?action=builder');
                exit;
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit-links'])) {
        $title = $_POST['title'];
        $url = $_POST['link'];

        $sql = "INSERT INTO links (profile_id, title, url, position, is_active) VALUES (:profile_id, :title, :url, :position, :is_active)";
        $stmt = $pdo->prepare($sql);
        $request = $stmt->execute([
            ':profile_id' => $userProfile['id'],
            ':title' => $title,
            ':url' => $url,
            ':position' => 0,
            ':is_active' => 1
        ]);

        if ($request) {
            header('Location: index.php?action=builder');
            exit;
        }
    }
}



?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Builder - LinkMe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .preview-avatar {
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .bg-preview {
            transition: background 0.3s;
            min-height: 400px;
            border-radius: 20px;
        }
    </style>
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="index.php?action=index">
                LinkMe
            </a>

            <div class="d-flex align-items-center gap-3">

                <span class="text-muted d-none d-md-inline">
                    Bonjour, <strong>
                        <?= htmlspecialchars($user['username']) ?>
                    </strong>
                </span>

                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Menu
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>
                            <a class="dropdown-item" href="index.php?action=edit_profil&id=<?= $user['id'] ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-person me-2" viewBox="0 0 16 16">
                                    <path
                                        d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664z" />
                                </svg>
                                Mon profil (Éditer)
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item"
                                href="index.php?action=profil&u=<?= htmlspecialchars($user['username']) ?>"
                                target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-eye me-2" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                    <path
                                        d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                </svg>
                                Voir ma page
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item text-danger" href="index.php?action=logout">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-box-arrow-right me-2" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 0h-8A1.5 1.5 0 0 0 0 1.5v9A1.5 1.5 0 0 0 1.5 12h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                                    <path fill-rule="evenodd"
                                        d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                                </svg>
                                Déconnexion
                            </a>
                        </li>

                    </ul>
                </div>

            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="row">

            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h2 class="h5 mb-0 fw-bold">Configuration du Profil</h2>
                    </div>

                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data">

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Photo de profil</label>
                                <div class="d-flex align-items-center gap-3">
                                    <?php
                                    $avatarSrc = !empty($userProfile['avatar_path']) ? $userProfile['avatar_path'] : 'images/user_default.png';
                                    ?>
                                    <img src="<?= BASE_URL . htmlspecialchars($avatarSrc) ?>" alt="Avatar"
                                        class="rounded-circle" width="80" height="80" style="object-fit:cover;">
                                    <input type="file" name="avatar_path" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nom affiché</label>
                                <input type="text" class="form-control" name="display_name"
                                    value="<?= isset($userProfile['display_name']) ? htmlspecialchars($userProfile['display_name']) : '' ?>"
                                    placeholder="Ex: Jean Dev">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Biographie</label>
                                <textarea class="form-control" name="bio"
                                    rows="3"><?= isset($userProfile['bio']) ? htmlspecialchars($userProfile['bio']) : '' ?></textarea>
                            </div>

                            <div class="row mb-4">
                                <div class="col-4">
                                    <label class="form-label fw-semibold">Couleur arrière-plan</label>
                                    <input type="color" class="form-control form-control-color w-100"
                                        name="theme_color_bg"
                                        value="<?= isset($userProfile['theme_color_bg']) ? htmlspecialchars($userProfile['theme_color_bg']) : '' ?>">
                                </div>
                                <div class="col-4">
                                    <label class="form-label fw-semibold">Couleur des boutons</label>
                                    <input type="color" class="form-control form-control-color w-100"
                                        name="theme_color_btn"
                                        value="<?= isset($userProfile['theme_color_btn']) ? htmlspecialchars($userProfile['theme_color_btn']) : '' ?>">
                                </div>
                                <div class="col-4">
                                    <label class="form-label fw-semibold">Couleur de police</label>
                                    <input type="color" class="form-control form-control-color w-100"
                                        name="theme_color_font"
                                        value="<?= isset($userProfile['theme_color_font']) ? htmlspecialchars($userProfile['theme_color_font']) : '' ?>">
                                </div>
                            </div>

                            <div class="d-grid mb-5">
                                <button type="submit" name="submit-profil" class="btn btn-primary">
                                    Enregistrer les informations
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="h6 fw-bold mb-0">Mes liens</h3>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#addLinkModal">
                                + Ajouter un lien
                            </button>
                        </div>

                        <div id="linksList">
                            <?php if (isset($userLinks)): ?>
                                <?php foreach ($userLinks as $link): ?>

                                    <div class="card mb-2 bg-light border-0">
                                        <div class="card-body p-2">
                                            <form action="index.php?action=edit_link" method="POST"
                                                class="row g-2 align-items-center">
                                                <input type="hidden" name="link_id" value="<?= $link['id'] ?>">

                                                <div class="col-12">
                                                    <input type="text" name="title" class="form-control form-control-sm fw-bold"
                                                        value="<?= htmlspecialchars($link['title']) ?>" placeholder="Titre">
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" name="url"
                                                        class="form-control form-control-sm text-muted"
                                                        value="<?= htmlspecialchars($link['url']) ?>" placeholder="URL">
                                                </div>

                                                <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                                                    <button type="submit" name="btn-edit" class="btn btn-xs btn-success py-0"
                                                        style="font-size: 0.8rem;">
                                                        OK
                                                    </button>

                                                    <button type="submit" name="btn-delete"
                                                        formaction="index.php?action=delete_link"
                                                        class="btn btn-xs btn-danger py-0" style="font-size: 0.8rem;"
                                                        onclick="return confirm('Supprimer ?')">
                                                        Suppr.
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="alert alert-info text-center py-2">Aucun lien. Cliquez sur "Ajouter".</div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="position-sticky" style="top: 20px;">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h3 class="h5 mb-0 fw-bold">Aperçu en direct</h3>
                            <span class="badge bg-success rounded-pill">Live</span>
                        </div>
                        <div class="card-body p-0">

                            <div class="preview-container text-center p-5 bg-preview d-flex flex-column align-items-center"
                                style="background-color: <?= isset($userProfile['theme_color_bg']) ? htmlspecialchars($userProfile['theme_color_bg']) : '' ?>;">

                                <img src="<?= BASE_URL . htmlspecialchars($avatarSrc) ?>"
                                    class="rounded-circle mb-3 preview-avatar" width="120" height="120">

                                <h2 class="h4 mb-2 fw-bold"
                                    style="color: <?= isset($userProfile['theme_color_font']) ? $userProfile['theme_color_font'] : '' ?>">
                                    <?= isset($userProfile['display_name']) ? htmlspecialchars($userProfile['display_name']) : '' ?>
                                </h2>
                                <p class="mb-4"
                                    style="color: <?= isset($userProfile['theme_color_font']) ? $userProfile['theme_color_font'] : '' ?>">
                                    <?= isset($userProfile['bio']) ? htmlspecialchars($userProfile['bio']) : '' ?>
                                </p>

                                <div class="d-flex flex-column gap-3 w-100 px-md-4">
                                    <?php if (isset($userLinks)): ?>
                                        <?php foreach ($userLinks as $link): ?>
                                            <a href="<?= htmlspecialchars($link['url']) ?>" target="_blank"
                                                class="btn btn-lg shadow-sm" style="background-color: <?= htmlspecialchars($userProfile['theme_color_btn']) ?>; 
                                  color: white; border: none;">
                                                <?= htmlspecialchars($link['title']) ?>
                                            </a>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mt-3 border-0">
                        <div class="card-body">
                            <label class="small text-muted mb-1">Votre lien public :</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" readonly
                                    value="<?= BASE_URL ?>public/index.php?action=profil&u=<?= htmlspecialchars($user['username']) ?>">
                                <button class="btn btn-outline-secondary"><a class=""
                                        href="index.php?action=profil&u=<?= htmlspecialchars($user['username']) ?>">Voir</a></button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addLinkModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nouveau lien</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Titre</label>
                            <input type="text" name="title" class="form-control" placeholder="Ex: Mon Instagram"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">URL</label>
                            <input type="url" name="link" class="form-control" placeholder="https://..." required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="submit-links" class="btn btn-primary">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>