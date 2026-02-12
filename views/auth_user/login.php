<?php
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $error = "Format d'email incorrect.";
    }

    if (empty($error)) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);

            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username']
            ];

            header('Location: index.php?action=builder');
            exit;

        } else {
            $error = 'Identifiants incorrects.';
        }
    }
}
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - LinkMe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-section">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 py-5">
            <div class="col-md-5 col-lg-4">
                <div class="text-center mb-4">
                    <a href="index.html" class="text-decoration-none">
                        <h1 class="fw-bold fs-2">
                            <span class="text-gradient">LinkMe</span>
                        </h1>
                    </a>
                    <p class="text-muted">Bon retour parmi nous !</p>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form action="" method="POST">
                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email"
                                    required placeholder="email@exemple.com" autocomplete="email">
                            </div>

                            <!-- Mot de passe -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control form-control-lg" id="password"
                                    name="password" placeholder="••••••••" autocomplete="current-password" required>
                                <?php if (!empty($error)): ?>
                                    <span class="form-text text-danger">
                                        <?= $error ?>
                                    </span>
                                <?php endif; ?>

                            </div>

                            <!-- Bouton submit -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Se connecter
                                </button>
                            </div>

                            <hr class="my-4">

                            <!-- Lien inscription -->
                            <div class="text-center">
                                <p class="text-muted mb-0">
                                    Pas encore de compte ?
                                    <a href="index.php?action=register" class="text-decoration-none">S'inscrire</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Lien retour -->
                <div class="text-center mt-4">
                    <a href="index.php?action=index" class="text-decoration-none text-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                        </svg>
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>