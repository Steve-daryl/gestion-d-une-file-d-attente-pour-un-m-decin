<?php
include('../db/db.php');
session_start();

$stmt = $pdo->query("SELECT * FROM patients");
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
$i=1;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Patients</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .table-responsive {
            overflow-x: auto;
        }
        .badge-sex {
            font-size: 0.9rem;
            padding: 5px 10px;
        }
        .male {
            background-color: #0d6efd;
        }
        .female {
            background-color: #fd7e14;
        }
        .action-btn {
            margin: 0 3px;
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="header-section">
                    <h3 class="mb-0"><i class="fas fa-user-injured me-2"></i>Liste des Patients</h3>
                    <div>
                        <a href="../admin/dashboard.php" class="btn btn-light btn-sm ms-2">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Naissance</th>
                                <th>Sexe</th>
                                <th>Téléphone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($patients as $patient): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($patient['nom']); ?></td>
                                <td><?= htmlspecialchars($patient['prenom']); ?></td>
                                <td><?= date('d/m/Y', strtotime($patient['date_naissance'])); ?></td>
                                <td>
                                    <span class="badge rounded-pill badge-sex <?= strtolower($patient['sexe']) === 'm' ? 'male' : 'female' ?>">
                                        <?= $patient['sexe'] === 'M' ? 'Masculin' : 'Féminin' ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($patient['telephone']); ?></td>
                                <td>
                                    <div class="d-flex">
                                        <a href="modifierPatients.php?id=<?= $patient['id_patient']; ?>" 
                                           class="btn btn-sm btn-primary action-btn" 
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="supprimerPatients.php?id=<?= $patient['id_patient']; ?>" 
                                           class="btn btn-sm btn-danger action-btn" 
                                           title="Supprimer"
                                           onclick="return confirm('Voulez-vous supprimer ce patient ?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                        <a href="detailsPatient.php?id=<?= $patient['id_patient']; ?>" 
                                           class="btn btn-sm btn-info action-btn" 
                                           title="Détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-muted">
                Total: <?= count($patients); ?> patient(s)
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>