<?php
include "config.php";

$nb_non_lus = 0;
if (isset($_SESSION['id_user'])) {
    $res_notif = mysqli_query($conn, "SELECT COUNT(*) AS total FROM messages WHERE id_destinataire = " . (int)$_SESSION['id_user'] . " AND lu = 0");
    $ligne_notif = mysqli_fetch_assoc($res_notif);
    $nb_non_lus = $ligne_notif['total'];
}

$sql = "SELECT * FROM annonces WHERE 1=1";

if (isset($_GET['prix_min']) && $_GET['prix_min'] != "") {
    $prix_min = (int) $_GET['prix_min'];
    $sql = $sql . " AND prix >= " . $prix_min;
}

if (isset($_GET['prix_max']) && $_GET['prix_max'] != "") {
    $prix_max = (int) $_GET['prix_max'];
    $sql = $sql . " AND prix <= " . $prix_max;
}

if (isset($_GET['categorie']) && $_GET['categorie'] != "") {
    $categorie = mysqli_real_escape_string($conn, $_GET['categorie']);
    $sql = $sql . " AND categorie = '" . $categorie . "'";
}

$sql = $sql . " ORDER BY date_creation DESC";

$resultat = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fleexi - Accueil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Fleexi</h1>
    <nav>
        <?php if (isset($_SESSION['id_user'])) { ?>
            <a href="annonces/creer.php">Deposer une annonce</a>
            <a href="annonces/mes_annonces.php">Mes annonces</a>
            <a href="favoris/mes_favoris.php">Mes favoris</a>
            <a href="messages/mes_messages.php">Mes messages<?php if ($nb_non_lus > 0) { ?> <span class="badge"><?php echo $nb_non_lus; ?></span><?php } ?></a>
            <a href="compte/profil.php">Mon compte</a>
            <a href="auth/deconnexion.php">Deconnexion</a>
        <?php } else { ?>
            <a href="auth/connexion.php">Connexion</a>
            <a href="auth/inscription.php">Inscription</a>
        <?php } ?>
    </nav>
</header>

<div class="contenu">
    <h2>Toutes les annonces</h2>

    <form method="GET" class="filtre-form">
        <input type="number" name="prix_min" placeholder="Prix minimum">
        <input type="number" name="prix_max" placeholder="Prix maximum">
        <select name="categorie">
            <option value="">Toutes les categories</option>
            <option value="Sport">Sport</option>
            <option value="Maison">Maison</option>
            <option value="High-Tech">High-Tech</option>
            <option value="Vetements">Vetements</option>
            <option value="Autre">Autre</option>
        </select>
        <div class="filtre-actions">
            <button type="submit" class="bouton">Filtrer</button>
            <a href="index.php" class="bouton bouton-gris">Reinitialiser</a>
        </div>
    </form>

    <br>

    <div class="cartes">
        <?php
        while ($annonce = mysqli_fetch_assoc($resultat)) {
            ?>
            <div class="carte">
                <img src="uploads/<?php echo $annonce['photo']; ?>" alt="photo">
                <h3><?php echo $annonce['titre']; ?></h3>
                <p class="prix"><?php echo $annonce['prix']; ?> &euro;</p>
                <a href="annonces/detail.php?id=<?php echo $annonce['id']; ?>" class="bouton">Voir l'annonce</a>
            </div>
            <?php
        }
        ?>
    </div>
</div>

</body>
</html>
