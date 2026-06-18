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

$id_user = $_SESSION['id_user'];
$message = "";

if (isset($_POST['changer_photo'])) {

    if ($_FILES['photo_profil']['name'] == "") {
        $message = "Veuillez choisir une image.";
    } else {
        $nom_photo = time() . "_" . $_FILES['photo_profil']['name'];
        move_uploaded_file($_FILES['photo_profil']['tmp_name'], "../uploads/" . $nom_photo);

        $req = mysqli_prepare($conn, "UPDATE users SET photo_profil = ? WHERE id = ?");
        mysqli_stmt_bind_param($req, "si", $nom_photo, $id_user);
        mysqli_stmt_execute($req);

        $message = "Votre photo de profil a ete mise a jour.";
    }
}

$req = mysqli_prepare($conn, "SELECT nom, prenom, mail, photo_profil FROM users WHERE id = ?");
mysqli_stmt_bind_param($req, "i", $id_user);
mysqli_stmt_execute($req);
$resultat = mysqli_stmt_get_result($req);
$utilisateur = mysqli_fetch_assoc($resultat);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon compte - Fleexi</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<header>
    <h1>Fleexi</h1>
    <nav>
        <a href="../index.php">Accueil</a>
        <a href="../annonces/mes_annonces.php">Mes annonces</a>
        <a href="../favoris/mes_favoris.php">Mes favoris</a>
        <a href="../messages/mes_messages.php">Mes messages<?php if ($nb_non_lus > 0) { ?> <span class="badge"><?php echo $nb_non_lus; ?></span><?php } ?></a>
        <a href="../auth/deconnexion.php">Deconnexion</a>
    </nav>
</header>

<div class="contenu">
    <h2>Mon compte</h2>

    <?php if ($message != "") { ?>
        <div class="message"><?php echo $message; ?></div>
    <?php } ?>

    <?php if ($utilisateur['photo_profil'] != "") { ?>
        <img src="../uploads/<?php echo $utilisateur['photo_profil']; ?>" alt="photo de profil"
             style="width:150px; height:150px; object-fit:cover; border-radius:50%;">
    <?php } else { ?>
        <p>Aucune photo de profil pour le moment.</p>
    <?php } ?>

    <p><strong>Prenom :</strong> <?php echo $utilisateur['prenom']; ?></p>
    <p><strong>Nom :</strong> <?php echo $utilisateur['nom']; ?></p>
    <p><strong>Email :</strong> <?php echo $utilisateur['mail']; ?></p>

    <h3>Changer ma photo de profil</h3>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="photo_profil">
        <button type="submit" name="changer_photo" class="bouton">Enregistrer la photo</button>
    </form>
</div>

</body>
</html>
