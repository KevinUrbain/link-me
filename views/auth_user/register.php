<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    $errors = [];

    if (empty($username)) {
        $errors['username'] = 'Le pseudo est obligatoire';
    }

    if (empty($email)) {
        $errors['email'] = "L'email est obligatoire";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = "Le format d'email n'est pas correct";
    }

    if (empty($password)) {
        $errors['password'] = 'Le mot de passe est obligatoire';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Le mot de passe doit faire au moins 8 caractères';
    } elseif ($password !== $confirmPassword) {
        $errors['password'] = 'Les mots de passe ne correspondent pas';
    }

    if (empty($errors)) {
        $sqlCheck = "SELECT id FROM users WHERE email = :email OR username = :username";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute([':email' => $email, ':username' => $username]);

        if ($stmtCheck->fetch()) {
            $errors['duplicate'] = "Ce pseudo ou cet email est déjà utilisé.";
        }
    }

    if (empty($errors)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)";

        $stmt = $pdo->prepare($sql);
        try {
            $success = $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password_hash' => $passwordHash
            ]);

            if ($success) {
                header('Location: index.php?action=login&success=1');
                exit;
            }
        } catch (PDOException $e) {
            $errors['bdd'] = "Erreur lors de l'inscription.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - LinkMe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-section">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 py-5">
            <div class="col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <a href="index.html" class="text-decoration-none">
                        <h1 class="fw-bold fs-2">
                            <span class="text-gradient">LinkMe</span>
                        </h1>
                    </a>
                    <p class="text-muted">Créez votre compte</p>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <!-- Pseudo -->
                            <div class="mb-3">
                                <label for="username" class="form-label">Pseudo <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="votre_pseudo" pattern="[a-zA-Z0-9_]{3,20}"
                                    title="3-20 caractères (lettres, chiffres et underscore)">
                                <div class="form-text">Ce sera votre URL : linkme.com/votre_pseudo</div>
                                <?php if (!empty($errors['username'])): ?>
                                    <span class="form-text text-danger"><?= $errors['username'] ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required
                                    placeholder="email@exemple.com">
                                <?php if (!empty($errors['email'])): ?>
                                    <span class="form-text text-danger"><?= $errors['email'] ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- Mot de passe -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required
                                    minlength="8" placeholder="••••••••">
                                <div class="form-text">Au moins 8 caractères</div>
                            </div>

                            <!-- Confirmation mot de passe -->
                            <div class="mb-4">
                                <label for="confirmPassword" class="form-label">Confirmer le mot de passe <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                                    required minlength="8" placeholder="••••••••">
                                <?php if (!empty($errors['password'])): ?>
                                    <span class="form-text text-danger">
                                        <?= $errors['password'] ?>
                                    </span>
                                <?php endif; ?>
                            </div>



                            <!-- Bouton submit -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Créer mon compte
                                </button>
                            </div>

                            <!-- Lien connexion -->
                            <div class="text-center">
                                <p class="text-muted mb-0">
                                    Vous avez déjà un compte ?
                                    <a href="index.php?action=login" class="text-decoration-none">Se connecter</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>