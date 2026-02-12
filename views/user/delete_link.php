<?php

if (!isset($_SESSION['user']['id'])) {
    header('Location: ');
    exit;
}

$userId = $_SESSION['user']['id'];

$stmt2 = $pdo->prepare("SELECT * FROM profiles WHERE user_id = ?");
$stmt2->execute([$userId]);
$userProfile = $stmt2->fetch();

if (!$userProfile) {
    die("Profil introuvable. Veuillez d'abord créer votre profil.");
}

if (isset($_GET['action']) && $_GET['action'] === 'delete_link') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn-delete'])) {
        $linkId = $_POST['link_id'];
        $sql = "DELETE FROM links WHERE id = :id AND profile_id = :profile_id";
        $stmt = $pdo->prepare($sql);
        $request = $stmt->execute([
            ':id' => $linkId,
            ':profile_id' => $userProfile['id']
        ]);
        if ($request) {
            header('Location: index.php?action=builder');
            exit;
        }
    }
}