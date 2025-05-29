<?php
include('../db/db.php');
session_start();

// Récupérer la liste des services avec les médecins affiliés
$stmt = $pdo->query("SELECT s.id_service, s.nom_service, u.nom AS nom_medecin 
                     FROM service s
                     LEFT JOIN utilisateur u ON s.id_service = u.id_service 
                     WHERE u.role = 'medecin'");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
$i = 1;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Services</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .no-doctor {
            color: #6c757d;
            font-style: italic;
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
                    <h3 class="mb-0"><i class="fas fa-hospital me-2"></i>Liste des Services</h3>
                    <a href="../admin/dashboard.php" class="btn btn-light">
                        <i class="me-1"></i>Retour
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nom du Service</th>
                                <th>Médecin Référent</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($services as $service): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($service['nom_service']); ?></strong>
                                </td>
                                <td class="<?= !isset($service['nom_medecin']) ? 'no-doctor' : '' ?>">
                                    <?= isset($service['nom_medecin']) ? htmlspecialchars($service['nom_medecin']) : 'Aucun médecin affilié' ?>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="modifierService.php?id=<?= $service['id_service'] ?>" 
                                           class="btn btn-sm btn-warning me-2"
                                           title="Modifier">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <a href="supprimerService.php?id=<?= $service['id_service'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           title="Supprimer"
                                           onclick="return confirm('Voulez-vous vraiment supprimer ce service ?');">
                                            <i class="fas fa-trash-alt"></i> Supprimer
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
                Total: <?= count($services); ?> service(s)
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>