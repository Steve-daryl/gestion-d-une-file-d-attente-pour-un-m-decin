<?php
include('../db/db.php');


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_patient = $_POST['id_patient'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_naissance = $_POST['date_naissance'];
    $sexe = $_POST['sexe'];
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];

    // Vérifier si le patient existe
    $check_sql = "SELECT * FROM patients WHERE id_patient = :id";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->bindParam(':id', $id_patient);
    $check_stmt->execute();
    if (!$check_stmt->fetch()) {
        die("Erreur : patient non trouvé.");
    }

    $sql = "UPDATE patients 
            SET nom = :nom, prenom = :prenom, date_naissance = :date_naissance, sexe = :sexe, 
                telephone = :telephone, adresse = :adresse
            WHERE id_patient = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_patient);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':date_naissance', $date_naissance);
    $stmt->bindParam(':sexe', $sexe);
    $stmt->bindParam(':telephone', $telephone);
    $stmt->bindParam(':adresse', $adresse);

    if ($stmt->execute()) {
        echo "Modification Reussite avec Success!!!!";
        header("Location: ../patients/listpatient.php");
        exit;
    } else {
        echo "Erreur lors de la mise à jour.";
    }
}
?>