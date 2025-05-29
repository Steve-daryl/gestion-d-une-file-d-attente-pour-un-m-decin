<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Médecin</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #f8f9fa; 
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-custom {
            background-color: #27ae60;
            padding: 12px 20px;
        }
        .table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 30px;
        }
        .btn-action {
            padding: 5px 10px;
            font-size: 14px;
            margin-right: 5px;
        }
        .no-data {
            color: #6c757d;
            font-style: italic;
            padding: 15px;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'medecin') {
        header("Location: ../login.php");
        exit;
    }

    require_once('../db/db.php');

    $id_medecin = $_SESSION['id_user'];

    $req_today = $pdo->prepare("
        SELECT c.*, p.nom, p.prenom
        FROM consultation c
        INNER JOIN patients p ON c.id_patient = p.id_patient
        WHERE c.id_medecin = ? 
        AND c.date_consultation = CURDATE()
        AND c.statut != 'terminé'
        ORDER BY c.heure_arrivee
    ");

    $req_today->execute([$id_medecin]);
    $patients_du_jour = $req_today->fetchAll();

    $req_hist = $pdo->prepare("
        SELECT c.*, p.nom, p.prenom
        FROM consultation c
        INNER JOIN patients p ON c.id_patient = p.id_patient
        WHERE c.id_medecin = ? AND c.statut IN ('terminé', 'annulé')
        ORDER BY c.date_consultation DESC
    ");
    $req_hist->execute([$id_medecin]);
    $historique = $req_hist->fetchAll();
    ?>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom mb-4">
        <div class="container-fluid">
            <span class="navbar-brand">Médecin : <?php echo $_SESSION['nom']; ?></span>
            <a href="deconnexion.php" class="btn btn-outline-light btn-sm">Déconnexion</a>
        </div>
    </nav>

    <div class="table-container">
        <h2 class="h4 mb-3">Patients du jour</h2>
        <?php if (count($patients_du_jour) > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Heure arrivée</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($patients_du_jour as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['prenom'] . ' ' . $p['nom']) ?></td>
                        <td><?= $p['heure_arrivee'] ?></td>
                        <td><?= $p['statut'] ?></td>
                        <td>
                            <?php if ($p['statut'] == 'en attente'): ?>
                                <a class="btn btn-primary btn-action" 
                                   href="changementStatut.php?id=<?= $p['id_consultation'] ?>&statut=en consultation">
                                   Consultation
                                </a>
                            <?php endif; ?>
                            <?php if ($p['statut'] != 'terminé'): ?>
                                <a class="btn btn-success btn-action" 
                                   href="changementStatut.php?id=<?= $p['id_consultation'] ?>&statut=terminé">
                                   Terminer
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p class="no-data">Aucun patient prévu aujourd'hui.</p>
        <?php endif; ?>
    </div>

    <div class="table-container">
        <h2 class="h4 mb-3">Historique des consultations</h2>
        <?php if (count($historique) > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Statut</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historique as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?></td>
                        <td><?= $c['date_consultation'] ?></td>
                        <td><?= $c['heure_arrivee'] ?></td>
                        <td><?= $c['statut'] ?></td>
                        <td><?= $c['commentaire'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p class="no-data">Aucune consultation passée.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>