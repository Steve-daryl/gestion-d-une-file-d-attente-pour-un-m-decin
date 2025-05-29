<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'infirmier') {
    header("Location: ../login.php");
    exit;
}

require_once('../db/db.php');

// Récupération des services et médecins
$services = $pdo->query("SELECT * FROM service")->fetchAll(PDO::FETCH_ASSOC);
$medecins = $pdo->query("SELECT * FROM utilisateur WHERE role = 'medecin'")->fetchAll(PDO::FETCH_ASSOC);

// Paramètres de filtrage
$periode = $_GET['periode'] ?? '30'; // Par défaut 30 derniers jours
$date_debut = $_GET['date_debut'] ?? '';
$date_fin = $_GET['date_fin'] ?? '';

// Déterminer la période
if ($date_debut && $date_fin) {
    $where_periode = "c.date_consultation BETWEEN '$date_debut' AND '$date_fin'";
    $periode_label = "Du $date_debut au $date_fin";
} else {
    $where_periode = "c.date_consultation >= DATE_SUB(CURDATE(), INTERVAL $periode DAY)";
    $periode_label = "Derniers $periode jours";
}

// Statistiques par service
$sql_service = "SELECT s.nom_service, COUNT(c.id_consultation) as nb_consultations
                FROM consultation c
                JOIN utilisateur u ON c.id_medecin = u.id_user
                JOIN service s ON u.id_service = s.id_service
                WHERE $where_periode
                GROUP BY s.id_service, s.nom_service
                ORDER BY nb_consultations DESC";
$stats_services = $pdo->query($sql_service)->fetchAll(PDO::FETCH_ASSOC);

// Statistiques par médecin
$sql_medecin = "SELECT CONCAT(u.nom, ' ', u.prenom) as nom_complet, s.nom_service, COUNT(c.id_consultation) as nb_consultations
                FROM consultation c
                JOIN utilisateur u ON c.id_medecin = u.id_user
                JOIN service s ON u.id_service = s.id_service
                WHERE $where_periode
                GROUP BY u.id_user, u.nom, u.prenom, s.nom_service
                ORDER BY nb_consultations DESC";
$stats_medecins = $pdo->query($sql_medecin)->fetchAll(PDO::FETCH_ASSOC);

// Évolution par jour (pour le graphique temporel)
$sql_evolution = "SELECT DATE(c.date_consultation) as date_cons, COUNT(c.id_consultation) as nb_consultations
                  FROM consultation c
                  WHERE $where_periode
                  GROUP BY DATE(c.date_consultation)
                  ORDER BY date_cons ASC";
$evolution_data = $pdo->query($sql_evolution)->fetchAll(PDO::FETCH_ASSOC);

// Statistiques par statut
$sql_statut = "SELECT c.statut, COUNT(c.id_consultation) as nb_consultations
               FROM consultation c
               WHERE $where_periode
               GROUP BY c.statut";
$stats_statuts = $pdo->query($sql_statut)->fetchAll(PDO::FETCH_ASSOC);

// Préparer les données pour les graphiques
$services_labels = json_encode(array_column($stats_services, 'nom_service'));
$services_data = json_encode(array_column($stats_services, 'nb_consultations'));

$medecins_labels = json_encode(array_column($stats_medecins, 'nom_complet'));
$medecins_data = json_encode(array_column($stats_medecins, 'nb_consultations'));

$evolution_labels = json_encode(array_column($evolution_data, 'date_cons'));
$evolution_values = json_encode(array_column($evolution_data, 'nb_consultations'));

$statuts_labels = json_encode(array_column($stats_statuts, 'statut'));
$statuts_data = json_encode(array_column($stats_statuts, 'nb_consultations'));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques - Dashboard Infirmier</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
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
        .sidebar a:hover, .sidebar a.active {
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
        .chart-container {
            position: relative;
            height: 400px;
            margin-bottom: 30px;
        }
        .stats-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .stat-item:last-child {
            border-bottom: none;
        }
        .stat-number {
            font-weight: bold;
            color: #2c3e50;
            font-size: 1.2em;
        }
        .filters-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h4>Infirmier</h4>
    <a href="dashboard.php">🏠 Tableau de bord</a>
    <a href="../patients/ajouterPatients.php">➕ Ajouter un patient</a>
    <a href="../patients/listpatient.php">👥 Liste des patients</a>
    <a href="../consultations/ajouterConsultation.php">🩺 Mettre en consultation</a>
    <a href="statistiques.php" class="active">📊 Statistiques</a>
</div>

<div class="content">
    <div class="navbar-custom">
        <span>Bienvenue Infirmier : <?= $_SESSION['nom']; ?></span>
        <a href="deconnexion.php" class="text-white">Déconnexion</a>
    </div>

    <h2>📊 Statistiques des consultations</h2>
    <p class="text-muted">Période analysée : <?= $periode_label ?></p>

    <!-- Filtres -->
    <div class="filters-section">
        <h5>Filtrer par période</h5>
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Période prédéfinie</label>
                <select name="periode" class="form-select">
                    <option value="7" <?= $periode == '7' ? 'selected' : '' ?>>7 derniers jours</option>
                    <option value="30" <?= $periode == '30' ? 'selected' : '' ?>>30 derniers jours</option>
                    <option value="90" <?= $periode == '90' ? 'selected' : '' ?>>3 derniers mois</option>
                    <option value="365" <?= $periode == '365' ? 'selected' : '' ?>>12 derniers mois</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date de début</label>
                <input type="date" name="date_debut" class="form-control" value="<?= $date_debut ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Date de fin</label>
                <input type="date" name="date_fin" class="form-control" value="<?= $date_fin ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block">Filtrer</button>
            </div>
        </form>
    </div>

    <!-- Graphiques et statistiques -->
    <div class="stats-grid">
        <!-- Évolution temporelle -->
        <div class="stats-card" style="grid-column: 1 / -1;">
            <h5>📈 Évolution des consultations</h5>
            <div class="chart-container">
                <canvas id="evolutionChart"></canvas>
            </div>
        </div>

        <!-- Statistiques par service -->
        <div class="stats-card">
            <h5>🏥 Consultations par service</h5>
            <div class="chart-container">
                <canvas id="servicesChart"></canvas>
            </div>
            <hr>
            <h6>Détails numériques :</h6>
            <?php foreach ($stats_services as $stat): ?>
                <div class="stat-item">
                    <span><?= htmlspecialchars($stat['nom_service']) ?></span>
                    <span class="stat-number"><?= $stat['nb_consultations'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Statistiques par médecin -->
        <div class="stats-card">
            <h5>👨‍⚕️ Consultations par médecin</h5>
            <div class="chart-container">
                <canvas id="medecinsChart"></canvas>
            </div>
            <hr>
            <h6>Détails numériques :</h6>
            <?php foreach ($stats_medecins as $stat): ?>
                <div class="stat-item">
                    <div>
                        <strong><?= htmlspecialchars($stat['nom_complet']) ?></strong><br>
                        <small class="text-muted"><?= htmlspecialchars($stat['nom_service']) ?></small>
                    </div>
                    <span class="stat-number"><?= $stat['nb_consultations'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Statistiques par statut -->
        <div class="stats-card">
            <h5>📋 Répartition par statut</h5>
            <div class="chart-container">
                <canvas id="statutsChart"></canvas>
            </div>
            <hr>
            <h6>Détails numériques :</h6>
            <?php foreach ($stats_statuts as $stat): ?>
                <div class="stat-item">
                    <span><?= htmlspecialchars(ucfirst($stat['statut'])) ?></span>
                    <span class="stat-number"><?= $stat['nb_consultations'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Résumé global -->
        <div class="stats-card">
            <h5>📊 Résumé global</h5>
            <?php 
            $total_consultations = array_sum(array_column($stats_services, 'nb_consultations'));
            $nb_services_actifs = count($stats_services);
            $nb_medecins_actifs = count($stats_medecins);
            $moyenne_par_jour = count($evolution_data) > 0 ? round($total_consultations / count($evolution_data), 1) : 0;
            ?>
            <div class="stat-item">
                <span>Total des consultations</span>
                <span class="stat-number"><?= $total_consultations ?></span>
            </div>
            <div class="stat-item">
                <span>Services actifs</span>
                <span class="stat-number"><?= $nb_services_actifs ?></span>
            </div>
            <div class="stat-item">
                <span>Médecins actifs</span>
                <span class="stat-number"><?= $nb_medecins_actifs ?></span>
            </div>
            <div class="stat-item">
                <span>Moyenne par jour</span>
                <span class="stat-number"><?= $moyenne_par_jour ?></span>
            </div>
        </div>
    </div>
</div>

<script>
// Configuration générale des graphiques
Chart.defaults.responsive = true;
Chart.defaults.maintainAspectRatio = false;

// Graphique d'évolution temporelle
const evolutionChart = new Chart(document.getElementById('evolutionChart'), {
    type: 'line',
    data: {
        labels: <?= $evolution_labels ?>,
        datasets: [{
            label: 'Nombre de consultations',
            data: <?= $evolution_values ?>,
            borderColor: '#3498db',
            backgroundColor: 'rgba(52, 152, 219, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        plugins: {
            title: {
                display: true,
                text: 'Évolution quotidienne des consultations'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Graphique des services
const servicesChart = new Chart(document.getElementById('servicesChart'), {
    type: 'bar',
    data: {
        labels: <?= $services_labels ?>,
        datasets: [{
            label: 'Consultations',
            data: <?= $services_data ?>,
            backgroundColor: [
                '#3498db', '#e74c3c', '#2ecc71', '#f39c12', 
                '#9b59b6', '#1abc9c', '#34495e', '#e67e22'
            ]
        }]
    },
    options: {
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Graphique des médecins
const medecinsChart = new Chart(document.getElementById('medecinsChart'), {
    type: 'horizontalBar',
    data: {
        labels: <?= $medecins_labels ?>,
        datasets: [{
            label: 'Consultations',
            data: <?= $medecins_data ?>,
            backgroundColor: '#2ecc71'
        }]
    },
    options: {
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Graphique des statuts
const statutsChart = new Chart(document.getElementById('statutsChart'), {
    type: 'doughnut',
    data: {
        labels: <?= $statuts_labels ?>,
        datasets: [{
            data: <?= $statuts_data ?>,
            backgroundColor: ['#3498db', '#f39c12', '#2ecc71', '#e74c3c']
        }]
    },
    options: {
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

</body>
</html>