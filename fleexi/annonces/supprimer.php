<?php
include "../config.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/connexion.php");
    exit();
}

$id = (int) $_GET['id'];

if (isset($_POST['confirmer'])) {

    $req1 = mysqli_prepare($conn, "DELETE FROM messages WHERE id_annonce = ?");
    mysqli_stmt_bind_param($req1, "i", $id);
    mysqli_stmt_execute($req1);

    $req2 = mysqli_prepare($conn, "DELETE FROM favoris WHERE id_annonce = ?");
    mysqli_stmt_bind_param($req2, "i", $id);
    mysqli_stmt_execute($req2);

    $req3 = mysqli_prepare($conn, "DELETE FROM annonces WHERE id = ? AND id_user = ?");
    mysqli_stmt_bind_param($req3, "ii", $id, $_SESSION['id_user']);
    mysqli_stmt_execute($req3);

    header("Location: mes_annonces.php");
    exit();
}

$req = mysqli_prepare($conn, "SELECT titre FROM annonces WHERE id = ? AND id_user = ?");
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
    <title>Supprimer - Fleexi</title>
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
    <h2>Supprimer l'annonce</h2>

    <div class="message">
        Etes-vous sur de vouloir supprimer l'annonce "<?php echo $annonce['titre']; ?>" ?
        Cette action est definitive.
    </div>

    <form method="POST">
        <button type="submit" name="confirmer" class="bouton bouton-rouge">Oui, supprimer</button>
        <a href="mes_annonces.php" class="bouton bouton-gris">Annuler</a>
    </form>
</div>

</body>
</html>
