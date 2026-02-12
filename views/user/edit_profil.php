<?php

if (!isset($_SESSION['user']['id'])) {
    header('Location: index.php?action=login');
    exit;
}

$userId = $_SESSION['user']['id'];

$id = (int) $_GET['id'];


if ($userId !== (int) $id) {
    header('Location: index.php?action=login');
    exit;
}

$stmt1 = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt1->execute([$userId]);
$user = $stmt1->fetch();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']) ?? '';
    $username = trim($_POST['username']) ?? '';

    $oldPasswordInDb = $user['password_hash']; //Mot de passe en DB
    $oldPassword = trim($_POST['old_password']);
    $newPassword = trim($_POST['new_password']);

    if (password_verify($oldPassword, $oldPasswordInDb)) {
        $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE users SET password_hash = ?, email = ?, username = ? WHERE id = ?");
        $update = $stmt->execute([$newPassword, $email, $username, $userId]);

        if ($update) {
            $message = "<div class='alert alert-success'>Mot de passe modifié avec succès !</div>";
            header('Location: index.php?action=edit_profil&id=' . $user['id']);
            exit;
        } else {
            $message = "<div class='alert alert-danger'>L'ancien mot de passe est incorrect.</div>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditer mon profil - <?= $user['username'] ?? '' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-light">
    <!-- Header -->
    <nav class="navbar navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.html">
                <span class="">LinkMe</span>
            </a>
            <a href="index.php?action=builder" class="btn btn-sm btn-outline-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                </svg>
                Retour
            </a>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5 col-lg-4">
                <!-- En-tête -->
                <div class="text-center mb-4">
                    <h1 class="h3 fw-bold mb-2">Éditer mon profil</h1>
                    <p class="text-muted">Modifiez vos informations de compte</p>
                </div>

                <!-- Formulaire -->
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form action="" method="POST">
                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?= htmlspecialchars($user['email']) ?? '' ?>" required>
                            </div>

                            <!-- Pseudo -->
                            <div class="mb-3">
                                <label for="username" class="form-label">Pseudo</label>
                                <div class="input-group">
                                    <span class="input-group-text">linkme.com/</span>
                                    <input type="text" class="form-control" id="username" name="username"
                                        value="<?= htmlspecialchars($user['username']) ?? '' ?>"
                                        pattern="[a-zA-Z0-9_]{3,20}" required>
                                </div>
                                <div class="form-text">3-20 caractères (lettres, chiffres et underscore)</div>
                            </div>

                            <hr class="my-4">

                            <!-- Section mot de passe -->
                            <h6 class="mb-3">Modifier le mot de passe</h6>

                            <!-- Mot de passe -->
                            <div class="mb-3">
                                <label for="oldPassword" class="form-label">Mot de passe actuel</label>
                                <input type="password" name="old_password" class="form-control" id="oldPassword"
                                    required minlength="8">
                                <div class="form-text">Laissez vide si vous ne souhaitez pas changer le mot de passe
                                </div>
                            </div>

                            <!-- Nouveau mot de passe -->
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">Nouveau mot de passe</label>
                                <input type="password" name="new_password" class="form-control" required minlength="8">
                            </div>

                            <!-- Boutons -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Enregistrer les modifications
                                </button>
                                <a href="index.php?action=builder" class="btn btn-outline-secondary">
                                    Annuler
                                </a>
                            </div>
                        </form>
                        <?= $message ?? '' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>