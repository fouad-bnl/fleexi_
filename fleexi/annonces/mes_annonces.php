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

$req = mysqli_prepare($conn, "SELECT * FROM annonces WHERE id_user = ? ORDER BY date_creation DESC");
mysqli_stmt_bind_param($req, "i", $_SESSION['id_user']);
mysqli_stmt_execute($req);
$resultat = mysqli_stmt_get_result($req);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes annonces - Fleexi</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<header>
    <h1>Fleexi</h1>
    <nav>
        <a href="../index.php">Accueil</a>
        <a href="creer.php">Deposer une annonce</a>
        <a href="../favoris/mes_favoris.php">Mes favoris</a>
        <a href="../messages/mes_messages.php">Mes messages<?php if ($nb_non_lus > 0) { ?> <span class="badge"><?php echo $nb_non_lus; ?></span><?php } ?></a>
        <a href="../compte/profil.php">Mon compte</a>
        <a href="../auth/deconnexion.php">Deconnexion</a>
    </nav>
</header>

<div class="contenu">
    <h2>Mes annonces</h2>

    <div class="cartes">
        <?php
        while ($annonce = mysqli_fetch_assoc($resultat)) {
            ?>
            <div class="carte">
                <img src="../uploads/<?php echo $annonce['photo']; ?>" alt="photo">
                <h3><?php echo $annonce['titre']; ?></h3>
                <p class="prix"><?php echo $annonce['prix']; ?> &euro;</p>
                <a href="modifier.php?id=<?php echo $annonce['id']; ?>" class="bouton">Modifier</a>
                <a href="supprimer.php?id=<?php echo $annonce['id']; ?>" class="bouton bouton-rouge">Supprimer</a>
            </div>
            <?php
        }
        ?>
    </div>
</div>

</body>
</html>
