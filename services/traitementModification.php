<?php
include('db/db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_service = $_POST['id_service'];
    $nom_service = trim($_POST['nom_service']);

    $stmt = $pdo->prepare("UPDATE Service SET nom_service = ? WHERE id_service = ?");
    if ($stmt->execute([$nom_service, $id_service])) {
        echo "Modification Réussi avec Success!!!";
        header("Location: ../admin/dashboard.php");
                exit;
    } else {
        echo "Erreur lors de la Modification";
    }
}
?>