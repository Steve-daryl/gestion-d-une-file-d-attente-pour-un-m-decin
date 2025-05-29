<?php
include('../db/db.php');
session_start();

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM Utilisateur WHERE id_user = ?");
    if ($stmt->execute([$_GET['id']])) {
        header("Location: listeUser.php");
        echo "Supprimer avec success";
        exit;
    } else {
        echo "Erreur lors de la suppression.";
    }
} else {
    echo "ID non fourni.";
}
?>