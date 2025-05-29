<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <title>Ajouter un Utilisateur</title>
    <script>
        function toggleFields() {
            var role = document.getElementById("role_select").value;
            document.getElementById("medecin_fields").style.display = (role === "medecin") ? "block" : "none";
        }
    </script>
</head>
<body>

<body class="bg-light">
    <div class="container mt-5">
        <a href="javascript:history.back()" class="btn btn-secondary mb-3">⬅️ Retour</a>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Ajout d'un utilisateur</h2>

                        <form action="traitementAjoutuser.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nom</label>
                                <input type="text" name="nom" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Prénom</label>
                                <input type="text" name="prenom" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mot de passe</label>
                                <input type="password" name="mot_de_passe" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Rôle</label>
                                <select name="role" id="role_select" class="form-select" onchange="toggleFields()" required>
                                    <option value="">Veuillez Choisir un role</option>
                                    <?php
                                    include('../db/db.php');
                                    $stmt = $pdo->query("SHOW COLUMNS FROM utilisateur LIKE 'role'");
                                    $column = $stmt->fetch();
                                    preg_match("/^enum\((.*)\)$/", $column['Type'], $matches);
                                    $roles = explode(",", $matches[1]);

                                    foreach ($roles as $r) {
                                        $role = trim($r, "'");
                                        echo "<option value=\"$role\">$role</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- <div id="medecin_fields" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label">Spécialité</label>
                                    <select name="specialite" class="form-select">
                                        <option value="Cardiologie">Cardiologie</option>
                                        <option value="Neurologie">Neurologie</option>
                                        <option value="Pédiatrie">Pédiatrie</option>
                                        <option value="Chirurgie">Chirurgie</option>
                                    </select>
                                </div> -->

                                <div class="mb-3">
                                    <label class="form-label">Service</label>
                                    <select name="id_service" class="form-select">
                                        <?php
                                        $services = $pdo->query("SELECT id_service, nom_service FROM service")->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($services as $service) {
                                            echo "<option value='{$service['id_service']}'>{$service['nom_service']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFields() {
            const role = document.getElementById('role_select').value;
            const medecinFields = document.getElementById('medecin_fields');
            medecinFields.style.display = (role === 'medecin') ? 'block' : 'none';
        }
    </script>
</body>


</body>
</html>