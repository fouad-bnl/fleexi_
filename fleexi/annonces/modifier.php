<?php
include "../config.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/connexion.php");
    exit();
}

$id = (int) $_GET['id'];

if (isset($_POST['enregistrer'])) {
    $titre = $_POST['titre'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];
    $categorie = $_POST['categorie'];

    $req = mysqli_prepare($conn, "UPDATE annonces SET titre = ?, prix = ?, description = ?, categorie = ? WHERE id = ? AND id_user = ?");
    mysqli_stmt_bind_param($req, "sdssii", $titre, $prix, $description, $categorie, $id, $_SESSION['id_user']);
    mysqli_stmt_execute($req);

    header("Location: mes_annonces.php");
    exit();
}

$req = mysqli_prepare($conn, "SELECT * FROM annonces WHERE id = ? AND id_user = ?");
mysqli_stmt_bind_param($req, "ii", $id, $_SESSION['id_user']);
mysqli_stmt_execute($req);
$resultat = mysqli_stmt_get_result($req);
$annonce = mysqli_fetch_assoc($resultat);

if (!$annonce) {
    header("Location: mes_annonces.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'annonce - Fleexi</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<header>
    <h1>Fleexi</h1>
    <nav>
        <a href="../index.php">Accueil</a>
        <a href="mes_annonces.php">Mes annonces</a>
        <a href="../auth/deconnexion.php">Deconnexion</a>
    </nav>
</header>

<div class="contenu">
    <h2>Modifier l'annonce</h2>

    <form method="POST">
        <input type="text" name="titre" value="<?php echo $annonce['titre']; ?>">
        <input type="number" name="prix" value="<?php echo $annonce['prix']; ?>">
        <textarea name="description"><?php echo $annonce['description']; ?></textarea>
        <select name="categorie">
            <option value="Sport" <?php if ($annonce['categorie'] == "Sport") echo "selected"; ?>>Sport</option>
            <option value="Maison" <?php if ($annonce['categorie'] == "Maison") echo "selected"; ?>>Maison</option>
            <option value="High-Tech" <?php if ($annonce['categorie'] == "High-Tech") echo "selected"; ?>>High-Tech</option>
            <option value="Vetements" <?php if ($annonce['categorie'] == "Vetements") echo "selected"; ?>>Vetements</option>
            <option value="Autre" <?php if ($annonce['categorie'] == "Autre") echo "selected"; ?>>Autre</option>
        </select>
        <button type="submit" name="enregistrer" class="bouton">Enregistrer</button>
        <a href="mes_annonces.php" class="bouton bouton-gris">Annuler</a>
    </form>
</div>

</body>
</html>
