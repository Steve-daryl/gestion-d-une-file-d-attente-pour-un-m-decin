<?php
include('../db/db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom_service = trim($_POST['nom_service']);

    // Vérifier si le service existe déjà
    $check_sql = "SELECT COUNT(*) FROM service WHERE nom_service = :nom_service";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->bindParam(':nom_service', $nom_service);
    $check_stmt->execute();
    if ($check_stmt->fetchColumn() > 0) {
        die("Erreur : Ce service existe déjà.");
    }

    $sql = "INSERT INTO service (nom_service) VALUES (:nom_service)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nom_service', $nom_service);

    if ($stmt->execute()) {
        echo "Service ajouté avec succès.";
        header("Location: ../admin/dashboard.php");
        exit;
    } else {
        echo "Erreur lors de l'ajout du service.";
    }
}
?>