<?php
include('../db/db.php');
session_start();

if (isset($_GET['id']) && isset($_GET['statut'])) {
    $id_consultation = $_GET['id'];
    $nouveau_statut = $_GET['statut'];

    $stmt = $pdo->prepare("UPDATE consultation SET statut = ? WHERE id_consultation = ?");
    if ($stmt->execute([$nouveau_statut, $id_consultation])) {
        header("Location: dashboardMede.php");
        exit;
    } else {
        echo "Erreur lors de la mise à jour du statut.";
    }
} else {
    echo "Paramètres invalides.";
}
?>