<?php
session_start();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../login.php");
        exit;
    }

    require_once('../db/db.php');
    $total_users = $pdo->query("SELECT COUNT(*) FROM utilisateur")->fetchColumn();
    $total_patients = $pdo->query("SELECT COUNT(*) FROM patients")->fetchColumn();
    $total_consultations = $pdo->query("SELECT COUNT(*) FROM consultation")->fetchColumn();
    $total_services = $pdo->query("SELECT COUNT(*) FROM service")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Administrateur</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        background-color: #f5f7fa;
    }

    /* Sidebar */
    .sidebar {
        width: 240px;
        background-color: #2c3e50;
        color: #fff;
        min-height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        overflow-y: auto;
        padding-top: 30px;
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.2);
    }

    .sidebar h3 {
        margin-left: 20px;
        margin-bottom: 20px;
        font-weight: 600;
        font-size: 1.2rem;
    }

    .sidebar a {
        display: block;
        padding: 12px 20px;
        color: #ecf0f1;
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .sidebar a:hover {
        background-color: #1abc9c;
        color: #fff;
    }

    .content {
        margin-left: 240px;
        padding: 30px;
        flex-grow: 1;
    }

    .navbar {
        background-color: #2980b9;
        color: white;
        padding: 15px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 6px;
    }

    .stats {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin: 30px 0;
    }

    .card {
        background-color: #fff;
        padding: 25px;
        border-radius: 10px;
        flex: 1 1 200px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        text-align: center;
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card h2 {
        font-size: 2rem;
        margin-bottom: 10px;
        color: #2980b9;
    }

    .card p {
        margin: 0;
        color: #555;
        font-weight: 500;
    }

    .card a {
        margin-top: 10px;
        display: inline-block;
        color: #2980b9;
        text-decoration: none;
        font-weight: bold;
    }

    .card a:hover {
        text-decoration: underline;
    }

    /* Footer */
    .footer {
        margin-top: 40px;
        text-align: center;
        color: #666;
        border-top: 1px solid #ddd;
        padding: 15px 0;
        font-size: 0.9rem;
    }

</style>

</head>
<body>

<div class="sidebar">
    <h3>Admin</h3>
    <a href="#">Dashboard</a>

    <a href="../patients/ajouterPatients.php">Ajouter Patients</a>

    <a href="ajouterUser.php"> Ajouter Utilisateurs</a>

    <a href="../consultations/ajouterConsultation.php">Ajouter Consultations</a>

    <a href="../services/ajouterservices.php">Ajouter Service</a>
</div>

<div class="content">
    <div class="navbar">
        <span>Bienvenue, <?php echo $_SESSION['nom']; ?></span>
        <a href="deconnexion.php" style="color: white;">Déconnexion</a>
    </div>

    <h2>Tableau de bord</h2>
    <div class="stats">
        <div class="card">
            <h2><?= $total_users ?></h2>
            <p>Utilisateurs</p>
            <a href="listeUser.php">Voir la liste</a>
        </div>
        <div class="card">
            <h2><?= $total_patients ?></h2>
            <p>Patients</p>
            <a href="../patients/listpatient.php">Voir la liste</a>
        </div>
        <div class="card">
            <h2><?= $total_consultations ?></h2>
            <p>Consultations</p>
            <a href="../consultations/listesConsultations.php">Voir la liste</a>
        </div>
        <div class="card">
            <h2><?= $total_services ?></h2>
            <p>Services</p>
            <a href="../services/listeService.php">Voir la liste</a>
        </div>
    </div>

    <div class="footer">
        &copy; <?= date('Y') ?> - Plateforme de gestion médicale
    </div>
</div>
    <script>
        setInterval(() => {
            location.reload();
        }, 15000); 
    </script>
</body>
</html>
