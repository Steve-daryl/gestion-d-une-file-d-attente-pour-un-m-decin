<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'infirmier') {
    header("Location: ../login.php");
    exit;
}

require_once('../db/db.php');

$services = $pdo->query("SELECT * FROM service")->fetchAll(PDO::FETCH_ASSOC);
$medecins = $pdo->query("SELECT * FROM utilisateur WHERE role = 'medecin'")->fetchAll(PDO::FETCH_ASSOC);

// Filtres
$id_service = $_GET['service'] ?? null;
$id_medecin = $_GET['medecin'] ?? null;

$sql = "SELECT c.*, p.nom, p.prenom, u.nom AS nom_medecin, u.id_service
        FROM consultation c
        JOIN patients p ON c.id_patient = p.id_patient
        JOIN utilisateur u ON c.id_medecin = u.id_user
        WHERE c.date_consultation = CURDATE()";

$params = [];

if ($id_service) {
    $sql .= " AND u.id_service = ?";
    $params[] = $id_service;
}

if ($id_medecin) {
    $sql .= " AND u.id_user = ?";
    $params[] = $id_medecin;
}

$sql .= " ORDER BY c.heure_arrivee ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$file = $stmt->fetchAll(PDO::FETCH_ASSOC);

$serviceList = [];
foreach ($services as $service) {
    $serviceList[$service['id_service']] = $service['nom_service'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Infirmier</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-utilities.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            width: 220px;
            background-color: #2c3e50;
            color: white;
            padding-top: 20px;
            flex-shrink: 0;
        }
        .sidebar h4 {
            text-align: center;
            margin-bottom: 20px;
        }
        .sidebar a {
            display: block;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #34495e;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .navbar-custom {
            background-color: #e67e22;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
        }
        table {
            width: 100%;
        }
        th, td {
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .filters {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h4>Infirmier</h4>
    <a href="#">🏠 Tableau de bord</a>
    <a href="../patients/ajouterPatients.php">➕ Ajouter un patient</a>
    <a href="../patients/listpatient.php">👥 Liste des patients</a>
    <a href="../consultations/ajouterConsultation.php">🩺 Mettre en consultation</a>
</div>

<div class="content">
    <div class="navbar-custom">
        <span>Bienvenue Infirmier : <?= $_SESSION['nom']; ?></span>
        <a href="deconnexion.php" class="text-white">Déconnexion</a>
    </div>

    <h2>📋 File d’attente du jour</h2>

    <div class="filters">
        <form method="GET" class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="service">Service :</label>
                <select name="service" class="form-select">
                    <option value="">Tous</option>
                    <?php foreach ($services as $s): ?>
                        <option value="<?= $s['id_service'] ?>" <?= ($s['id_service'] == $id_service) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['nom_service']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-auto">
                <label for="medecin">Médecin :</label>
                <select name="medecin" class="form-select">
                    <option value="">Tous</option>
                    <?php foreach ($medecins as $m): ?>
                        <option value="<?= $m['id_user'] ?>" <?= ($m['id_user'] == $id_medecin) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($m['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-auto">
                <button type="submit" class="btn btn-primary mt-4">Filtrer</button>
            </div>
        </form>
    </div>

    <?php if ($file): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom du patient</th>
                    <th>Heure d'arrivée</th>
                    <th>Statut</th>
                    <th>Médecin</th>
                    <th>Service</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($file as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['prenom'] . ' ' . $row['nom']) ?></td>
                        <td><?= $row['heure_arrivee'] ?></td>
                        <td><?= htmlspecialchars($row['statut']) ?></td>
                        <td><?= htmlspecialchars($row['nom_medecin']) ?></td>
                        <td><?= $serviceList[$row['id_service']] ?? 'Non défini' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune consultation prévue aujourd’hui.</p>
    <?php endif; ?>
</div>
</body>
</html>
