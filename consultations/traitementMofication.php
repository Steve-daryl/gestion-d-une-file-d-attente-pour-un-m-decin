<?php
include('../db/db.php');
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_consultation = $_POST['id_consultation'];
    $statut = $_POST['statut'];
    $commentaire = $_POST['commentaire'];

    $stmt = $pdo->prepare("UPDATE Consultation SET statut = ?, commentaire = ? WHERE id_consultation = ?");
    if ($stmt->execute([$statut, $commentaire, $id_consultation])) {
        //header("Location: manage_consultations.php");
        exit;
    } else {
        echo "Erreur lors de la mise à jour.";
    }
}
?>