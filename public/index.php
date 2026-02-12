<?php
session_start();

require_once '../config/config.php';
require_once ROOT . '/config/database.php';

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'index':
        require_once ROOT . '/views/user/home.php';
        break;
    case 'register':
        require_once ROOT . '/views/auth_user/register.php';
        break;
    case 'login':
        require_once ROOT . '/views/auth_user/login.php';
        break;
    case 'builder':
        require_once ROOT . '/views/user/builder.php';
        break;
    case 'edit_link':
        require_once ROOT . '/views/user/edit_link.php';
        break;
    case 'delete_link':
        require_once ROOT . '/views/user/delete_link.php';
        break;
    case 'edit_profil':
        require_once ROOT . '/views/user/edit_profil.php';
        break;
    case 'logout':
        require_once ROOT . '/views/auth_user/logout.php';
        break;
    case 'profil':
        require_once ROOT . '/views/user/profil.php';
        break;
    default:
        require_once ROOT . '/views/user/404.php';
        break;
}