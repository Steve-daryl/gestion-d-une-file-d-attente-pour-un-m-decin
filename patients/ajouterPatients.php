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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout d'un Patient</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <a href="<?= $dashboardUrl ?>" class="btn btn-secondary mb-3">⬅️ Retour</a>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Ajout d'un Patient</h2>

                        <form action="traitementAjout.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nom</label>
                                <input type="text" name="nom" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Prénom</label>
                                <input type="text" name="prenom" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Date de naissance</label>
                                <input type="date" name="date_naissance" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sexe</label>
                                <select name="sexe" class="form-select" required>
                                    <option value="M">Masculin</option>
                                    <option value="F">Féminin</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Téléphone</label>
                                <input type="text" name="telephone" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Adresse</label>
                                <textarea name="adresse" class="form-control"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>