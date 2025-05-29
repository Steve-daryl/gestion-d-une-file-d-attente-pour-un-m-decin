<?php
session_start();

// Si l'utilisateur n'est pas connecté
if (!isset($_SESSION['id_user']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
} 

switch ($_SESSION['role']) {
    case 'admin':
        header("Location: admin/dashboard.php");
        break;
    case 'medecin':
        header("Location: admin/dashboardMede.php");
        break;
    case 'infirmier':
        header("Location: admin/dashboardInf.php");
        break;
    default:
        echo "Bien Vouloir Réessayer avec des informations correctes.";
        break;
}
