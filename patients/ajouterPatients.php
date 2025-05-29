    <?php
    include('../db/db.php');
    session_start();

    ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Patient</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <a href="javascript:history.back()" class="btn btn-secondary mb-3">⬅️ Retour</a>

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
                                <select name="sexe" class="form-select">
                                    <!-- <?php foreach ($sexe_options as $sexe): ?>
                                        <option value="<?= htmlspecialchars($sexe); ?>"><?= htmlspecialchars($sexe); ?></option>
                                    <?php endforeach; ?> -->
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
</body>

</html>