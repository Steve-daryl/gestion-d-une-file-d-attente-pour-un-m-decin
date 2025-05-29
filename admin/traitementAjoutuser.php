<?php
include('../db/db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Déterminer la valeur `id_service` si le rôle est 'medecin' ou 'admin'
    $id_service = null;

    if ($_POST['role'] === "medecin" || $_POST['role'] === "admin" || $_POST['role']==="infirmier") {
        $id_service = $_POST['id_service'];
    }

    // Préparation de la requête d'insertion
    $stmt = $pdo->prepare("INSERT INTO Utilisateur (nom, prenom, email, mot_de_passe, role, id_service) 
        VALUES (?, ?, ?, ?, ?, ?)");

    if ($stmt->execute([
        $_POST['nom'], 
        $_POST['prenom'], 
        $_POST['email'], 
        password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT), 
        $_POST['role'], 
        $id_service
    ])) {
        echo "Ajout Réussi avec succès";
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Erreur lors de l'ajout de l'utilisateur.";
    }
}
?>