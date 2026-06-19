<?php
include "../config.php";

$message = "";

if (isset($_POST['inscrire'])) {

    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];
    $mdp_confirm = $_POST['mdp_confirm'];

    if ($email == "") {
        $message = "L'email est obligatoire.";
    } else if ($mdp == "") {
        $message = "Le mot de passe est obligatoire.";
    } else if (strlen($mdp) < 10) {
        $message = "Le mot de passe doit faire au moins 10 caracteres.";
    } else if ($mdp != $mdp_confirm) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        $check = mysqli_prepare($conn, "SELECT id FROM users WHERE mail = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $message = "Cet email est deja utilise.";
        } else {
            $mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);

            $req = mysqli_prepare($conn, "INSERT INTO users (nom, prenom, mail, mdp) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($req, "ssss", $nom, $prenom, $email, $mdp_hache);
            mysqli_stmt_execute($req);

            header("Location: connexion.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription - Fleexi</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

    <header>
        <h1>Fleexi</h1>
        <nav>
            <a href="../index.php">Accueil</a>
            <a href="connexion.php">Connexion</a>
        </nav>
    </header>

    <div class="contenu">
        <h2>Inscription</h2>

        <?php if ($message != "") { ?>
            <div class="message">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">
            <input type="text" name="nom" placeholder="Nom">
            <input type="text" name="prenom" placeholder="Prenom">
            <input type="email" name="email" placeholder="Email">
            <input type="password" name="mdp" placeholder="Mot de passe (10 caracteres min)">
            <input type="password" name="mdp_confirm" placeholder="Confirmez le mot de passe">
            <button type="submit" name="inscrire" class="bouton">S'inscrire</button>
        </form>
    </div>

</body>

</html>