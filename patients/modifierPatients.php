<?php
include('../db/db.php');
$sql = "SHOW COLUMNS FROM patients WHERE Field = 'sexe'";
$stmt = $pdo->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Extraire les valeurs ENUM
preg_match("/^enum\((.+)\)$/", $row['Type'], $matches);
$sexe_options = explode(",", str_replace("'", "", $matches[1]));

if (isset($_GET['id'])) {
    $id_patient = $_GET['id'];

    // Récupérer les infos du patient
    $sql = "SELECT * FROM patients WHERE id_patient = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_patient);
    $stmt->execute();
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$patient) {
        die("Patient introuvable.");
    }
} else {
    die("ID du patient non fourni.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Patient</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .form-container {
            max-width: 800px;
            margin: 0 auto;
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
                    <i class="fas fa-user-edit me-2"></i>Modifier les informations du Patient
                </h2>
                <p class="text-muted">ID Patient: <?= $patient['id_patient']; ?></p>
            </div>

            <form action="traitementModification.php" method="POST">
                <input type="hidden" name="id_patient" value="<?= $patient['id_patient']; ?>">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nom" class="form-label required-field">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" 
                               value="<?= htmlspecialchars($patient['nom']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="prenom" class="form-label required-field">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" 
                               value="<?= htmlspecialchars($patient['prenom']); ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="date_naissance" class="form-label required-field">Date de naissance</label>
                        <input type="date" class="form-control" id="date_naissance" name="date_naissance" 
                               value="<?= $patient['date_naissance']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="sexe" class="form-label required-field">Sexe</label>
                        <select class="form-select" id="sexe" name="sexe" required>
                            <!-- <?php foreach ($sexe_options as $sexe): ?>
                                <option value="<?= $sexe; ?>" <?= $sexe == $patient['sexe'] ? 'selected' : ''; ?>>
                                    <?= $sexe; ?>
                                </option>
                            <?php endforeach; ?> -->
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="telephone" class="form-label required-field">Téléphone</label>
                    <input type="tel" class="form-control" id="telephone" name="telephone" 
                           value="<?= htmlspecialchars($patient['telephone']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?= isset($patient['email']) ? htmlspecialchars($patient['email']) : ''; ?>">
                </div>

                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse</label>
                    <textarea class="form-control" id="adresse" name="adresse" rows="3"><?= htmlspecialchars($patient['adresse']); ?></textarea>
                </div>

                <?php if (isset($patient['mutuelle'])): ?>
                <div class="mb-3">
                    <label for="mutuelle" class="form-label">Mutuelle</label>
                    <input type="text" class="form-control" id="mutuelle" name="mutuelle" 
                           value="<?= htmlspecialchars($patient['mutuelle']); ?>">
                </div>
                <?php endif; ?>

                <div class="form-footer d-flex justify-content-between">
                    <a href="listpatient.php?id=<?= $patient['id_patient']; ?>" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>