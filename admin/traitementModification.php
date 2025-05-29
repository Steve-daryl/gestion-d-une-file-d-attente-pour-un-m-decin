<?php
include('../db/db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Vérifier si l'utilisateur est un médecin
    // $specialite = ($_POST['role'] === "medecin") ? $_POST['specialite'] : null;
    $id_service = ($_POST['role'] === "medecin") ? $_POST['id_service'] : null;
    $id_service = ($_POST['role'] === "admin") ? $_POST['id_service'] : null;
    $id_service = ($_POST['role'] === "infirmier") ? $_POST['id_service'] : null;
    $stmt = $pdo->prepare("UPDATE Utilisateur SET nom = ?, email = ?, role = ?, id_service = ? WHERE id_user = ?");

    if ($stmt->execute([
        $_POST['nom'], 
        $_POST['email'], 
        $_POST['role'], 
        // $specialite, 
        $id_service, 
        $_POST['id_user']
    ])) {
        echo "Modification Réussie avec succès";
        exit;
    } else {
        echo "Erreur lors de la mise à jour.";
    }
}
?>