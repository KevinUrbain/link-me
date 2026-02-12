<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LinkMe - Tous vos liens en un seul endroit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Hero Section -->
    <div class="hero-section">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand fw-bold fs-3" href="index.php">
                    <span class="text-gradient">LinkMe</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=login">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-light rounded-pill px-4 ms-2"
                                href="index.php?action=register">S'inscrire</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container text-center hero-content">
            <h1 class="display-3 fw-bold mb-4">
                Tous vos liens en <span class="text-gradient-light">un seul endroit</span>
            </h1>
            <p class="lead mb-5 text-white-50">
                Créez votre page personnalisée et partagez tous vos liens importants avec une seule URL
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="index.php?action=register" class="btn btn-primary btn-lg rounded-pill px-5">
                    Commencer gratuitement
                </a>
                <a href="#features" class="btn btn-outline-light btn-lg rounded-pill px-5">
                    En savoir plus
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Pourquoi choisir LinkMe ?</h2>
                <p class="text-muted">Simple, rapide et efficace</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                class="bi bi-link-45deg" viewBox="0 0 16 16">
                                <path
                                    d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1 1 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4 4 0 0 1-.128-1.287z" />
                                <path
                                    d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243z" />
                            </svg>
                        </div>
                        <h3 class="h5 mb-3">Liens illimités</h3>
                        <p class="text-muted">Ajoutez autant de liens que vous le souhaitez sans aucune restriction</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                class="bi bi-palette" viewBox="0 0 16 16">
                                <path
                                    d="M8 5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3m4 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M5.5 7a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m.5 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3" />
                                <path
                                    d="M16 8c0 3.15-1.866 2.585-3.567 2.07C11.42 9.763 10.465 9.473 10 10c-.603.683-.475 1.819-.351 2.92C9.826 14.495 9.996 16 8 16a8 8 0 1 1 8-8m-8 7c.611 0 .654-.171.655-.176.078-.146.124-.464.07-1.119-.014-.168-.037-.37-.061-.591-.052-.464-.112-1.005-.118-1.462-.01-.707.083-1.61.704-2.314.369-.417.845-.578 1.272-.618.404-.038.812.026 1.16.104.343.077.702.186 1.025.284l.028.008c.346.105.658.199.953.266.653.148.904.083.991.024C14.717 9.38 15 9.161 15 8a7 7 0 1 0-7 7" />
                            </svg>
                        </div>
                        <h3 class="h5 mb-3">Personnalisable</h3>
                        <p class="text-muted">Créez une page qui vous ressemble avec votre photo et vos couleurs</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                class="bi bi-lightning-charge" viewBox="0 0 16 16">
                                <path
                                    d="M11.251.068a.5.5 0 0 1 .227.58L9.677 6.5H13a.5.5 0 0 1 .364.843l-8 8.5a.5.5 0 0 1-.842-.49L6.323 9.5H3a.5.5 0 0 1-.364-.843l8-8.5a.5.5 0 0 1 .615-.09zM4.157 8.5H7a.5.5 0 0 1 .478.647L6.11 13.59l5.732-6.09H9a.5.5 0 0 1-.478-.647L9.89 2.41z" />
                            </svg>
                        </div>
                        <h3 class="h5 mb-3">Ultra rapide</h3>
                        <p class="text-muted">Créez votre page en moins de 2 minutes et partagez-la instantanément</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-4 text-white">Prêt à commencer ?</h2>
            <p class="lead mb-4 text-white-50">Rejoignez des milliers d'utilisateurs satisfaits</p>
            <a href="index.php?action=register" class="btn btn-light btn-lg rounded-pill px-5">
                Créer ma page gratuitement
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 bg-dark text-white-50">
        <div class="container text-center">
            <p class="mb-0">&copy; <?= date('Y') ?> LinkMe. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>