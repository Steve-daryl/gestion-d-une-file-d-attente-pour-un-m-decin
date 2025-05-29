<?php
include('../db/db.php');
session_start();

if (!isset($_GET['id'])) {
    die("ID du patient non fourni.");
}

$id_patient = $_GET['id'];

// Supprimer le patient (les consultations associées seront supprimées automatiquement grâce à ON DELETE CASCADE)
$stmt = $pdo->prepare("DELETE FROM patients WHERE id_patient = ?");
if ($stmt->execute([$id_patient])) {
    header("Location: listpatient.php");
    exit;
} else {
    $errorInfo = $stmt->errorInfo();
    echo "Erreur lors de la suppression : " . htmlspecialchars($errorInfo[2]);
}
?>