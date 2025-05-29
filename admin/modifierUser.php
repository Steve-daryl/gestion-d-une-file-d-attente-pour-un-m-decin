<?php
include('../db/db.php');
session_start();

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE id_user = ?");
    $stmt->execute([$_GET['id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("Utilisateur introuvable.");
    }
}

$stmt = $pdo->query("SELECT DISTINCT role FROM Utilisateur");
$roles = $stmt->fetchAll(PDO::FETCH_COLUMN);

$services = $pdo->query("SELECT id_service, nom_service FROM service")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier Utilisateur</title>
    <!-- Utilisation de votre Bootstrap local -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .medecin-fields {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="form-container">
            <h2 class="mb-4">Modifier Utilisateur</h2>
            <form action="traitementmodification.php" method="POST" class="needs-validation" novalidate>
                <input type="hidden" name="id_user" value="<?= htmlspecialchars($user['id_user']); ?>">

                <div class="mb-3">
                    <label class="form-label">Nom :</label>
                    <input type="text" class="form-control" name="nom" value="<?= htmlspecialchars($user['nom']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email :</label>
                    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Rôle :</label>
                    <select class="form-select" name="role" id="role_select" onchange="toggleFields()">
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= htmlspecialchars($role); ?>" <?= $user['role'] == $role ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($role); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- <div id="medecin_fields" class="medecin-fields" style="display: <?= ($user['role'] == 'medecin') ? 'block' : 'none'; ?>;"> -->
                    <!-- <div class="mb-3">
                        <label class="form-label">Spécialité :</label>
                        <input type="text" class="form-control" name="specialite" value="<?= isset($user['specialite']) ? htmlspecialchars($user['specialite']) : ''; ?>">
                    </div> -->

                    <div class="mb-3">
                        <label class="form-label">Service :</label>
                        <select class="form-select" name="id_service">
                            <?php foreach ($services as $service): ?>
                                <option value="<?= $service['id_service']; ?>" <?= ($user['id_service'] == $service['id_service']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($service['nom_service']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <!-- </div> -->

                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="listeUser.php" class="btn btn-secondary">Retour</a>
            </form>
        </div>
    </div>

    
    <script>
        function toggleFields() {
            var role = document.getElementById("role_select").value;
            document.getElementById("medecin_fields").style.display = (role === "medecin") ? "block" : "none";
        }

        // Validation Bootstrap
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>
</html>