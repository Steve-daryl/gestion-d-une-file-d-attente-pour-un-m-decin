<?php
include('../db/db.php');
session_start();

if (!isset($_GET['id'])) {
    header('Location: listePatients.php');
    exit();
}

$patient_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM patients WHERE id_patient = ?");
$stmt->execute([$patient_id]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$patient) {
    header('Location: listePatients.php');
    exit();
}

$stmt = $pdo->prepare("SELECT c.*, u.nom AS medecin_nom 
                       FROM consultation c
                       JOIN utilisateur u ON c.id_medecin = u.id_user
                       WHERE c.id_patient = ?
                       ORDER BY c.date_consultation DESC");
$stmt->execute([$patient_id]);
$consultations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Patient - <?= htmlspecialchars($patient['nom']) ?></title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .patient-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .patient-header {
            background-color: #0d6efd;
            color: white;
            border-radius: 10px 10px 0 0 !important;
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
        .consultation-card {
            border-left: 4px solid #0d6efd;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        .consultation-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .statut-badge {
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="fas fa-user-injured me-2"></i>Fiche Patient
            </h1>
            <a href="listpatient.php" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </a>
        </div>

        <div class="card patient-card mb-4">
            <div class="card-header patient-header d-flex justify-content-between align-items-center">

                <div>
                    <a href="modifierPatients.php?id=<?= $patient['id_patient'] ?>" class="btn btn-sm btn-light me-2">
                        <i class="fas fa-edit me-1"></i> Modifier
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="fw-bold">ID Patient:</span>
                                <span><?= $patient['id_patient'] ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Date de naissance:</span>
                                <span><?= date('d/m/Y', strtotime($patient['date_naissance'])) ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Sexe:</span>
                                <span class="badge rounded-pill badge-sex <?= strtolower($patient['sexe']) === 'm' ? 'male' : 'female' ?>">
                                    <?= $patient['sexe'] === 'M' ? 'Masculin' : 'Féminin' ?>
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Téléphone:</span>
                                <span><?= htmlspecialchars($patient['telephone']) ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Email:</span>
                                <span><?= !empty($patient['email']) ? htmlspecialchars($patient['email']) : 'Non renseigné' ?></span>
                            </li>
                            <li class="list-group-item">
                                <span class="fw-bold">Adresse:</span>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($patient['adresse'])) ?></p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="h5 mb-3">
            <i class="fas fa-calendar-check me-2"></i>Historique des consultations
        </h3>

        <?php if (count($consultations) > 0): ?>
            <?php foreach ($consultations as $consultation): ?>
                <div class="card consultation-card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title">
                                    Consultation #<?= $consultation['id_consultation'] ?>
                                    <span class="statut-badge badge bg-<?= 
                                        ($consultation['statut'] == 'Terminé') ? 'success' : 
                                        (($consultation['statut'] == 'Annulé') ? 'danger' : 'warning'); ?> ms-2">
                                        <?= htmlspecialchars($consultation['statut']) ?>
                                    </span>
                                </h5>
                                <p class="card-text mb-1">
                                    <i class="fas fa-user-md me-2"></i>
                                    <?= htmlspecialchars($consultation['medecin_nom']) ?>
                                </p>
                                <p class="card-text mb-1">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    <?= date('d/m/Y H:i', strtotime($consultation['date_consultation'])) ?>
                                </p>
                                <?php if (!empty($consultation['diagnostic'])): ?>
                                    <p class="card-text mt-2">
                                        <span class="fw-bold">Diagnostic:</span>
                                        <?= nl2br(htmlspecialchars($consultation['diagnostic'])) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>Aucune consultation enregistrée pour ce patient.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>