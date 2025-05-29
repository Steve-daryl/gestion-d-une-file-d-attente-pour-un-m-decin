<?php
include('db/db.php');
session_start();

if (isset($_GET['id'])) {
    $id_service = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM Service WHERE id_service = ?");
    if ($stmt->execute([$id_service])) {
        header("Location: ../admin/dashboard.php");
        exit;
    } else {
        echo "Erreur lors de la suppression.";
    }
} else {
    echo "ID du service non fourni.";
}
?>