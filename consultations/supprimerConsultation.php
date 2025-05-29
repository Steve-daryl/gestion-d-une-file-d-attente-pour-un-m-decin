<?php
session_start();
include('../db/db.php');

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM Consultation WHERE id_consultation = ?");
    if ($stmt->execute([$_GET['id']])) {
        header("Location: listesConsultations.php");
        exit;
    } else {
        echo "Erreur lors de la suppression.";
    }
} else {
    echo "ID non fourni.";
}
?>