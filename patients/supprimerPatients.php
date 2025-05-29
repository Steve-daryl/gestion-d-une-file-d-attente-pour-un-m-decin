<?php
include('db/db.php');
session_start();
if (!isset($_GET['id'])) {
    die("ID du patient non fourni.");
}

$stmt = $pdo->prepare("DELETE FROM Patient WHERE id_patient = ?");
if ($stmt->execute([$_GET['id']])) {
    header("Location: manage_patients.php");
    exit;
} else {
    echo "Erreur lors de la suppression.";
}
?>