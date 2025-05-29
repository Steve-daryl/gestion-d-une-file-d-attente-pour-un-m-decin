<?php
include('../db/db.php');
session_start();
$stmt = $pdo->query("SELECT c.id_consultation, p.nom AS patient_nom, p.prenom AS patient_prenom, u.nom AS medecin_nom, c.date_consultation,c.heure_arrivee, c.statut 
                      FROM consultation c
                      JOIN patients p ON c.id_patient = p.id_patient
                      JOIN utilisateur u ON c.id_medecin = u.id_user");
$consultations = $stmt->fetchAll(PDO::FETCH_ASSOC);
$i =1;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste des Consultations</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }
        .table-container {
            margin-top: 20px;
        }
        .action-buttons a {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Liste des Consultations</h2>
            <a href="../admin/dashboard.php" class="btn btn-success">Retour</a>
        </div>
        
        <div class="table-responsive table-container">
            <table class="table table-striped table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Médecin</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($consultations as $consultation): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($consultation['patient_nom'] . " " . $consultation['patient_prenom']); ?></td>
                        <td><?= htmlspecialchars($consultation['medecin_nom']); ?></td>
                        <td><?= date('d/m/Y ', strtotime($consultation['date_consultation'])); ?></td>
                        <td><?= date('H:i', strtotime($consultation['heure_arrivee'])) ?></td>
                        <td>
                            <span class="badge bg-<?= 
                                ($consultation['statut'] == 'Terminé') ? 'success' : 
                                (($consultation['statut'] == 'Annulé') ? 'danger' : 'primary'); ?>">
                                <?= htmlspecialchars($consultation['statut']); ?>
                            </span>
                        </td>
                        <td class="action-buttons">
                            <!-- <a href="modifierConsultation.php?id=<?= $consultation['id_consultation']; ?>" class="btn btn-sm btn-warning">Modifier</a> -->
                            <a href="supprimerConsultation.php?id=<?= $consultation['id_consultation']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Voulez-vous supprimer cette consultation ?');">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>