<?php
include "../config.php";

$nb_non_lus = 0;
if (isset($_SESSION['id_user'])) {
    $res_notif = mysqli_query($conn, "SELECT COUNT(*) AS total FROM messages WHERE id_destinataire = " . (int)$_SESSION['id_user'] . " AND lu = 0");
    $ligne_notif = mysqli_fetch_assoc($res_notif);
    $nb_non_lus = $ligne_notif['total'];
}

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/connexion.php");
    exit();
}

$req = mysqli_prepare($conn, "SELECT annonces.* FROM favoris JOIN annonces ON favoris.id_annonce = annonces.id WHERE favoris.id_user = ?");
mysqli_stmt_bind_param($req, "i", $_SESSION['id_user']);
mysqli_stmt_execute($req);
$resultat = mysqli_stmt_get_result($req);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes favoris - Fleexi</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<header>
    <h1>Fleexi</h1>
    <nav>
        <a href="../index.php">Accueil</a>
        <a href="../annonces/mes_annonces.php">Mes annonces</a>
        <a href="../messages/mes_messages.php">Mes messages<?php if ($nb_non_lus > 0) { ?> <span class="badge"><?php echo $nb_non_lus; ?></span><?php } ?></a>
        <a href="../compte/profil.php">Mon compte</a>
        <a href="../auth/deconnexion.php">Deconnexion</a>
    </nav>
</header>

<div class="contenu">
    <h2>Mes favoris</h2>

    <div class="cartes">
        <?php
        while ($annonce = mysqli_fetch_assoc($resultat)) {
            ?>
            <div class="carte">
                <img src="../uploads/<?php echo $annonce['photo']; ?>" alt="photo">
                <h3><?php echo $annonce['titre']; ?></h3>
                <p class="prix"><?php echo $annonce['prix']; ?> &euro;</p>
                <a href="../annonces/detail.php?id=<?php echo $annonce['id']; ?>" class="bouton">Voir l'annonce</a>
                <a href="ajouter.php?id=<?php echo $annonce['id']; ?>" class="bouton bouton-rouge">Retirer</a>
            </div>
            <?php
        }
        ?>
    </div>
</div>

</body>
</html>
