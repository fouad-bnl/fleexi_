<?php


include "../config.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/connexion.php");
    exit();
}

$message = "";

if (isset($_POST['creer'])) {

    $titre = $_POST['titre'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];
    $categorie = $_POST['categorie'];
    $id_user = $_SESSION['id_user'];

    if ($titre == "" || $prix == "" || $description == "" || $_FILES['photo']['name'] == "") {
        $message = "Tous les champs (avec photo) sont obligatoires.";
    } else {
       
        $nom_photo = time() . "_" . $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/" . $nom_photo);

        $req = mysqli_prepare($conn, "INSERT INTO annonces (titre, prix, description, photo, categorie, id_user) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($req, "sdsssi", $titre, $prix, $description, $nom_photo, $categorie, $id_user);
        mysqli_stmt_execute($req);

        header("Location: mes_annonces.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Creer une annonce - Fleexi</title>
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
    <h2>Deposer une annonce</h2>

    <?php if ($message != "") { ?>
        <div class="message"><?php echo $message; ?></div>
    <?php } ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="titre" placeholder="Nom de l'annonce">
        <input type="number" name="prix" placeholder="Prix en euros">
        <textarea name="description" placeholder="Description"></textarea>
        <select name="categorie">
            <option value="Sport">Sport</option>
            <option value="Maison">Maison</option>
            <option value="High-Tech">High-Tech</option>
            <option value="Vetements">Vetements</option>
            <option value="Autre">Autre</option>
        </select>
        <input type="file" name="photo">
        <button type="submit" name="creer" class="bouton">Publier</button>
    </form>
</div>

</body>
</html>
