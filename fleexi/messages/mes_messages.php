<?php
include "../config.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/connexion.php");
    exit();
}

$id_user = $_SESSION['id_user'];

$req_notif = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM messages WHERE id_destinataire = ? AND lu = 0");
mysqli_stmt_bind_param($req_notif, "i", $id_user);
mysqli_stmt_execute($req_notif);
$res_notif = mysqli_stmt_get_result($req_notif);
$ligne_notif = mysqli_fetch_assoc($res_notif);
$nb_non_lus = $ligne_notif['total'];

$req = mysqli_prepare($conn, "
    SELECT DISTINCT
        messages.id_annonce,
        CASE WHEN messages.id_expediteur = ? THEN messages.id_destinataire
             ELSE messages.id_expediteur END AS id_autre
    FROM messages
    WHERE messages.id_expediteur = ? OR messages.id_destinataire = ?
");
mysqli_stmt_bind_param($req, "iii", $id_user, $id_user, $id_user);
mysqli_stmt_execute($req);
$resultat = mysqli_stmt_get_result($req);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes messages - Fleexi</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<header>
    <h1>Fleexi</h1>
    <nav>
        <a href="../index.php">Accueil</a>
        <a href="../annonces/mes_annonces.php">Mes annonces</a>
        <a href="../favoris/mes_favoris.php">Mes favoris</a>
        <a href="../messages/mes_messages.php">Mes messages
            <?php if ($nb_non_lus > 0) { ?><span class="badge"><?php echo $nb_non_lus; ?></span><?php } ?>
        </a>
        <a href="../compte/profil.php">Mon compte</a>
        <a href="../auth/deconnexion.php">Deconnexion</a>
    </nav>
</header>

<div class="contenu">
    <h2>Mes discussions</h2>

    <?php
    while ($disc = mysqli_fetch_assoc($resultat)) {

        $id_annonce = $disc['id_annonce'];
        $id_autre = $disc['id_autre'];

        $r1 = mysqli_prepare($conn, "SELECT titre FROM annonces WHERE id = ?");
        mysqli_stmt_bind_param($r1, "i", $id_annonce);
        mysqli_stmt_execute($r1);
        $annonce = mysqli_fetch_assoc(mysqli_stmt_get_result($r1));
        $titre_annonce = $annonce ? $annonce['titre'] : "Annonce supprimee";

        $r2 = mysqli_prepare($conn, "SELECT prenom FROM users WHERE id = ?");
        mysqli_stmt_bind_param($r2, "i", $id_autre);
        mysqli_stmt_execute($r2);
        $autre = mysqli_fetch_assoc(mysqli_stmt_get_result($r2));
        $prenom_autre = $autre ? $autre['prenom'] : "Utilisateur";

        $r3 = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM messages WHERE id_destinataire = ? AND id_expediteur = ? AND id_annonce = ? AND lu = 0");
        mysqli_stmt_bind_param($r3, "iii", $id_user, $id_autre, $id_annonce);
        mysqli_stmt_execute($r3);
        $ligne = mysqli_fetch_assoc(mysqli_stmt_get_result($r3));
        $non_lus_disc = $ligne['total'];
        ?>
        <div class="bloc-message">
            <p><strong>Discussion avec <?php echo $prenom_autre; ?></strong>
               <?php if ($non_lus_disc > 0) { ?><span class="badge"><?php echo $non_lus_disc; ?> nouveau(x)</span><?php } ?>
            </p>
            <p>Annonce : <?php echo $titre_annonce; ?></p>
            <a href="discussion.php?annonce=<?php echo $id_annonce; ?>&autre=<?php echo $id_autre; ?>" class="bouton">Ouvrir la discussion</a>
        </div>
        <?php
    }
    ?>
</div>

</body>
</html>
