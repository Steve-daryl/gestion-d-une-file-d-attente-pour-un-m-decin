<?php
include('../db/db.php');
session_start();

// Récupérer le rôle de l'utilisateur connecté
if (isset($_SESSION['id_user'])) {
    $stmt = $pdo->prepare("SELECT role FROM utilisateur WHERE id_user = ?");
    $stmt->execute([$_SESSION['id_user']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $role = $user['role'] ?? 'admin'; // Par défaut 'admin' si non trouvé
} else {
    $role = 'admin'; // Par défaut si pas de session
}

$dashboardUrl = ($role === 'infirmier') ? '../admin/dashboardInf.php' : '../admin/dashboard.php';

$stmt = $pdo->query("SELECT id_patient, nom, prenom FROM patients");
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT id_user, nom, prenom FROM utilisateur WHERE role = 'medecin'");
$medecins = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <a href="<?= $dashboardUrl ?>" class="btn btn-secondary mb-3">⬅️ Retour</a>

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
                                            <?= htmlspecialchars($medecin['nom'] . " " . $medecin['prenom']); ?>
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