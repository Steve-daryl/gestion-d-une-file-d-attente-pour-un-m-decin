<?php
include('../db/db.php');
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $pdo->prepare("INSERT INTO consultation (id_patient, id_medecin, heure_arrivee, statut, commentaire) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$_POST['id_patient'], $_POST['id_medecin'], $_POST['heure_arrivee'], $_POST['statut'], $_POST['commentaire']])) {
        header("Location: ../admin/dashboard.php");
        exit;
    } else {
        echo "Erreur lors de l'enregistrement.";
    }
}
?>