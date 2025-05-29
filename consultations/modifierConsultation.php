<?php
include('../db/db.php');
session_start();
if (isset($_GET['id'])) {
    $id_consultation = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM Consultation WHERE id_consultation = ?");
    $stmt->execute([$id_consultation]);
    $consultation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$consultation) {
        die("Consultation introuvable.");
    }
} else {
    die("ID de la consultation non fourni.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Consultation</title>
</head>
<body>
    <h2>Modifier Consultation</h2>
    <form action="traitementMofication.php" method="POST">
        <input type="hidden" name="id_consultation" value="<?= $consultation['id_consultation']; ?>">

        <label>Statut :</label>
        <select name="statut">
            <?php
            $stmt = $pdo->query("SELECT DISTINCT statut FROM Consultation");
            $statuts = $stmt->fetchAll(PDO::FETCH_COLUMN);
            foreach ($statuts as $statut) {
                echo "<option value='$statut' " . ($consultation['statut'] == $statut ? 'selected' : '') . ">$statut</option>";
            }
            ?>
        </select>

        <label>Commentaire :</label>
        <textarea name="commentaire"><?= htmlspecialchars($consultation['commentaire']); ?></textarea>

        <button type="submit">Modifier</button>
    </form>
</body>
</html>