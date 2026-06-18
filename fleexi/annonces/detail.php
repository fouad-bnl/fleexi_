<?php


include "../config.php";

$id = (int) $_GET['id'];
$req = mysqli_prepare($conn, "SELECT annonces.*, users.prenom AS vendeur, users.id AS id_vendeur FROM annonces JOIN users ON annonces.id_user = users.id WHERE annonces.id = ?");
mysqli_stmt_bind_param($req, "i", $id);
mysqli_stmt_execute($req);
$resultat = mysqli_stmt_get_result($req);
$annonce = mysqli_fetch_assoc($resultat);

if (!$annonce) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo $annonce['titre']; ?> - Fleexi</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<header>
    <h1>Fleexi</h1>
    <nav>
        <a href="../index.php">Accueil</a>
        <?php if (isset($_SESSION['id_user'])) { ?>
            <a href="mes_annonces.php">Mes annonces</a>
            <a href="../auth/deconnexion.php">Deconnexion</a>
        <?php } else { ?>
            <a href="../auth/connexion.php">Connexion</a>
        <?php } ?>
    </nav>
</header>

<div class="contenu detail">
    <h2><?php echo $annonce['titre']; ?></h2>
    <img src="../uploads/<?php echo $annonce['photo']; ?>" alt="photo">
    <p class="prix"><?php echo $annonce['prix']; ?> &euro;</p>
    <p><strong>Categorie :</strong> <?php echo $annonce['categorie']; ?></p>
    <p><strong>Description :</strong><br><?php echo $annonce['description']; ?></p>
    <p><strong>Vendeur :</strong> <?php echo $annonce['vendeur']; ?></p>

    <?php
    if (isset($_SESSION['id_user']) && $_SESSION['id_user'] != $annonce['id_vendeur']) {
        ?>
        <a href="../favoris/ajouter.php?id=<?php echo $annonce['id']; ?>" class="bouton">Ajouter / Retirer des favoris</a>

        <h3>Contacter le vendeur</h3>
        <form method="POST" action="../messages/envoyer.php">
            <input type="hidden" name="id_destinataire" value="<?php echo $annonce['id_vendeur']; ?>">
            <input type="hidden" name="id_annonce" value="<?php echo $annonce['id']; ?>">
            <textarea name="contenu" placeholder="Votre message..."></textarea>
            <button type="submit" name="envoyer" class="bouton">Envoyer le message</button>
        </form>
        <?php
    }
    ?>
</div>

</body>
</html>
