<?php
include('../db/db.php');
session_start();

if (isset($_GET['id'])) {
    $id_service = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM Service WHERE id_service = ?");
    $stmt->execute([$id_service]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        die("Service introuvable.");
    }
} else {
    die("ID du service non fourni.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Service</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .form-container {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .form-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .form-footer {
            border-top: 1px solid #eee;
            padding-top: 20px;
            margin-top: 20px;
        }
        .required-field::after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h2 class="text-primary">
                    <i class="fas fa-hospital me-2"></i>Modifier le Service
                </h2>
                <p class="text-muted">ID Service: <?= $service['id_service']; ?></p>
            </div>

            <form action="traitementModification.php" method="POST">
                <input type="hidden" name="id_service" value="<?= $service['id_service']; ?>">

                <div class="mb-4">
                    <label for="nom_service" class="form-label required-field">Nom du Service</label>
                    <input type="text" class="form-control form-control-lg" id="nom_service" name="nom_service" 
                           value="<?= htmlspecialchars($service['nom_service']); ?>" required>
                    <div class="form-text">Entrez le nouveau nom du service médical</div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Médecin référent (optionnel)</label>
                    <select class="form-select" name="medecin_referent">
                        <option value="">Choisir un Medecin</option>
                        <?php
                        $stmt = $pdo->query("SELECT id_user, nom FROM utilisateur WHERE role = 'medecin'");
                        $medecins = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach ($medecins as $medecin) {
                            $selected = ($medecin['id_user'] == $service['id_medecin']) ? 'selected' : '';
                            echo "<option value='{$medecin['id_user']}' $selected>{$medecin['nom']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-footer d-flex justify-content-between">
                    <a href="listeService.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>