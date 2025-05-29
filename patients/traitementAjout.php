<?php
include('../db/db.php');
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_naissance = $_POST['date_naissance'];
    $sexe = $_POST['sexe'];
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];

    $sql = "INSERT INTO patients (nom, prenom, date_naissance, sexe, telephone, adresse) 
            VALUES (:nom, :prenom, :date_naissance, :sexe, :telephone, :adresse)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':date_naissance', $date_naissance);
    $stmt->bindParam(':sexe', $sexe);
    $stmt->bindParam(':telephone', $telephone);
    $stmt->bindParam(':adresse', $adresse);
    
    if ($stmt->execute()) {
        echo "Patient ajouté avec succès.";
        header("Location: ../admin/dashboard.php");
        exit;
    } else {
        echo "Erreur lors de l'ajout du patient.";
    }
}
?>