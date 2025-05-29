<?php
include('../db/db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $pdo->prepare("INSERT INTO consultation (id_patient, id_medecin, commentaire) VALUES (?, ?, ?)");
    if ($stmt->execute([$_POST['id_patient'], $_POST['id_medecin'], $_POST['commentaire']])) {
        // Récupérer le rôle de l'utilisateur connecté
        if (isset($_SESSION['id_user'])) {
            $stmt_role = $pdo->prepare("SELECT role FROM utilisateur WHERE id_user = ?");
            $stmt_role->execute([$_SESSION['id_user']]);
            $user = $stmt_role->fetch(PDO::FETCH_ASSOC);
            $role = $user['role'] ?? 'admin'; // Par défaut 'admin' si non trouvé
        } else {
            $role = 'admin'; // Par défaut si pas de session
        }

        $dashboardUrl = ($role === 'infirmier') ? '../admin/dashboardInf.php' : '../admin/dashboard.php';
        
        header("Location: $dashboardUrl");
        exit;
    } else {
        echo "Erreur lors de l'enregistrement.";
    }
}
?>