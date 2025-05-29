<?php
include('../db/db.php');
session_start();

$stmt = $pdo->query("SELECT u.*, s.nom_service 
                     FROM utilisateur u 
                     LEFT JOIN service s ON u.id_service = s.id_service");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
$i =1;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Utilisateurs</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }
        .table-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="my-4">Liste des Utilisateurs</h2>
        <div class="table-responsive table-container">
            <table class="table table-striped table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <!-- <th>Spécialité</th> -->
                        <th>Service</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($user['nom']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td><?= htmlspecialchars($user['role']); ?></td>
                        <!-- <td><?= isset($user['specialite']) ? htmlspecialchars($user['specialite']) : 'Non défini' ?></td> -->
                        <td><?= isset($user['nom_service']) ? htmlspecialchars($user['nom_service']) : 'Non défini' ?></td>
                        <td>
                            <a href="modifierUser.php?id=<?= $user['id_user']; ?>" class="btn btn-sm btn-warning">Modifier</a>
                            <a href="supprimerUser.php?id=<?= $user['id_user']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Voulez-vous supprimer cet utilisateur ?');">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
                <a href="dashboard.php" class="btn btn-secondary">Retour</a>
        </div>
    </div>    
    <script>
        setInterval(() => {
            location.reload();
        }, 15000); 
    </script>
</body>
</html>