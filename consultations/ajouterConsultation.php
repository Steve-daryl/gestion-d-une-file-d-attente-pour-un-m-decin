<?php
include('../db/db.php');
session_start();
    $stmt = $pdo->query("SELECT id_patient, nom, prenom FROM patients");
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT id_user, nom FROM utilisateur WHERE role = 'medecin'");
    $medecins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SHOW COLUMNS FROM Consultation WHERE Field = 'statut'";
    $stmt = $pdo->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Extraire les valeurs ENUM
    preg_match("/^enum\((.+)\)$/", $row['Type'], $matches);
    $statuts = explode(",", str_replace("'", "", $matches[1]));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">

    <title>Ajouter Une Consultation</title>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <a href="javascript:history.back()" class="btn btn-secondary mb-3">⬅️ Retour</a>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Enregistrer une Consultation</h2>

                        <form action="traitementAjout.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Patient</label>
                                <select name="id_patient" class="form-select" required>
                                    <?php foreach ($patients as $patient): ?>
                                        <option value="<?= $patient['id_patient']; ?>">
                                            <?= htmlspecialchars($patient['nom'] . " " . $patient['prenom']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Médecin</label>
                                <select name="id_medecin" class="form-select" required>
                                    <?php foreach ($medecins as $medecin): ?>
                                        <option value="<?= $medecin['id_user']; ?>">
                                            <?= htmlspecialchars($medecin['nom']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Heure d'arrivée</label>
                                <input type="time" name="heure_arrivee" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Statut</label>
                                <select name="statut" class="form-select">
                                    <?php foreach ($statuts as $statut): ?>
                                        <option value="<?= htmlspecialchars($statut); ?>">
                                            <?= htmlspecialchars($statut); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="commentaire" class="form-control"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
