<?php
include('../db/db.php');
session_start();

// Initialiser la variable $patients
$patients = [];
$searchMessage = '';

// Vérifier si une recherche a été soumise
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $searchTerm = trim($_GET['search']);
    // Requête SQL pour rechercher dans la colonne 'nom'
    $query = "SELECT * FROM patients WHERE nom LIKE :searchTerm";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['searchTerm' => "%$searchTerm%"]);
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Message si aucun résultat
    if (count($patients) === 0) {
        $searchMessage = '<p class="text-center text-muted">Aucun patient trouvé pour "' . htmlspecialchars($searchTerm) . '"</p>';
    }
} else {
    // Si pas de recherche, afficher tous les patients
    $stmt = $pdo->query("SELECT * FROM patients");
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $searchMessage = '<p class="text-center text-muted">Veuillez entrer un nom pour effectuer une recherche.</p>';
}

$i = 1;

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
    <title>Liste des Patients</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .table-responsive {
            overflow-x: auto;
        }
        .badge-sex {
            font-size: 0.9rem;
            padding: 5px 10px;
        }
        .male {
            background-color: #0d6efd;
        }
        .female {
            background-color: #fd7e14;
        }
        .action-btn {
            margin: 0 3px;
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .main-wrapper {
            width: 100%;
            max-width: 1200px;
            padding: 0 15px;
            box-sizing: border-box;
        }
        .search-container {
            max-width: 600px;
            margin: 20px 0;
        }
        .search-form {
            position: relative;
            display: flex;
            width: 100%;
        }
        .search-input {
            width: 100%;
            padding: 10px 50px 10px 15px;
            font-size: 16px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-shadow: none;
            outline: none;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            box-sizing: border-box;
        }
        .search-input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .search-input::placeholder {
            color: #6c757d;
            opacity: 1;
        }
        .search-button {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            padding: 0 15px;
            background: #0d6efd;
            border: 1px solid #0d6efd;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
        }
        .search-button:hover {
            background: #0b5ed7;
            border-color: #0a58ca;
        }
        .search-button svg {
            fill: #fff;
        }
        @media (max-width: 576px) {
            .search-container {
                margin: 20px auto;
            }
            .search-input {
                font-size: 14px;
                padding: 8px 45px 8px 12px;
            }
            .search-button {
                padding: 0 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="header-section">
                    <h3 class="mb-0"><i class="fas fa-user-injured me-2"></i>Liste des Patients</h3>
                    <div>
                        <a href="<?= $dashboardUrl ?>" class="btn btn-light btn-sm ms-2">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                        <?php if (isset($_GET['search']) && !empty(trim($_GET['search']))): ?>
                            <a href="../patients/listpatient.php" class="btn btn-light btn-sm ms-2">
                                <i class="fas fa-list me-1"></i> Liste complète
                            </a>
                        <?php endif; ?>
                        <!-- Recherche -->
                        <div class="main-wrapper">
                            <div class="search-container">
                                <form class="search-form" method="GET" action="">
                                    <input class="search-input" type="search" name="search" placeholder="Rechercher un patient par nom..." aria-label="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                    <button class="search-button" type="submit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <!-- Fin recherche -->
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <?php if (count($patients) > 0): ?>
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Naissance</th>
                                    <th>Sexe</th>
                                    <th>Téléphone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($patients as $patient): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($patient['nom']); ?></td>
                                    <td><?= htmlspecialchars($patient['prenom']); ?></td>
                                    <td><?= date('d/m/Y', strtotime($patient['date_naissance'])); ?></td>
                                    <td>
                                        <span class="badge rounded-pill badge-sex <?= strtolower($patient['sexe']) === 'm' ? 'male' : 'female' ?>">
                                            <?= $patient['sexe'] === 'M' ? 'Masculin' : 'Féminin' ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($patient['telephone']); ?></td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="modifierPatients.php?id=<?= $patient['id_patient']; ?>" 
                                               class="btn btn-sm btn-primary action-btn" 
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="supprimerPatients.php?id=<?= $patient['id_patient']; ?>" 
                                               class="btn btn-sm btn-danger action-btn" 
                                               title="Supprimer"
                                               onclick="return confirm('Voulez-vous supprimer ce patient ?');">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                            <a href="detailsPatient.php?id=<?= $patient['id_patient']; ?>" 
                                               class="btn btn-sm btn-info action-btn" 
                                               title="Détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <?= $searchMessage ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-footer text-muted">
                Total: <?= count($patients); ?> patient(s)
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>