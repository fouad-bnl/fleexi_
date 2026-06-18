<?php
include "../config.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/connexion.php");
    exit();
}

$id_user = $_SESSION['id_user'];

$id_annonce = (int) $_GET['annonce'];
$id_autre = (int) $_GET['autre'];

$lire = mysqli_prepare($conn, "UPDATE messages SET lu = 1 WHERE id_destinataire = ? AND id_expediteur = ? AND id_annonce = ?");
mysqli_stmt_bind_param($lire, "iii", $id_user, $id_autre, $id_annonce);
mysqli_stmt_execute($lire);

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

$req = mysqli_prepare($conn, "
    SELECT * FROM messages
    WHERE id_annonce = ?
      AND ( (id_expediteur = ? AND id_destinataire = ?)
         OR (id_expediteur = ? AND id_destinataire = ?) )
    ORDER BY date_envoi ASC
");
mysqli_stmt_bind_param($req, "iiiii", $id_annonce, $id_user, $id_autre, $id_autre, $id_user);
mysqli_stmt_execute($req);
$resultat = mysqli_stmt_get_result($req);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Discussion - Fleexi</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<header>
    <h1>Fleexi</h1>
    <nav>
        <a href="../index.php">Accueil</a>
        <a href="mes_messages.php">Mes messages</a>
        <a href="../auth/deconnexion.php">Deconnexion</a>
    </nav>
</header>

<div class="contenu">
    <h2>Discussion avec <?php echo $prenom_autre; ?></h2>
    <p>Au sujet de : <strong><?php echo $titre_annonce; ?></strong></p>

    <div class="discussion">
        <?php
        while ($msg = mysqli_fetch_assoc($resultat)) {
            if ($msg['id_expediteur'] == $id_user) {
                $classe = "msg-moi";
                $qui = "Moi";
            } else {
                $classe = "msg-autre";
                $qui = $prenom_autre;
            }
            ?>
            <div class="<?php echo $classe; ?>">
                <p class="msg-qui"><?php echo $qui; ?></p>
                <p><?php echo $msg['contenu']; ?></p>
                <p class="msg-date"><?php echo $msg['date_envoi']; ?></p>
            </div>
            <?php
        }
        ?>
    </div>

    <form method="POST" action="envoyer.php">
        <input type="hidden" name="id_destinataire" value="<?php echo $id_autre; ?>">
        <input type="hidden" name="id_annonce" value="<?php echo $id_annonce; ?>">
        <textarea name="contenu" placeholder="Ecrire un message..."></textarea>
        <button type="submit" name="envoyer" class="bouton">Envoyer</button>
    </form>
</div>

</body>
</html>
