<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <title>Ajouter un Service</title>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <a href="javascript:history.back()" class="btn btn-secondary mb-3">⬅️ Retour</a>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Ajout d'un Service Médical</h2>

                        <form action="traitementAjout.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nom du Service :</label>
                                <input type="text" name="nom_service" class="form-control" required>
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
