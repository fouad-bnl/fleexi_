<?php
include "../config.php";

$message = "";

if (isset($_POST['connecter'])) {

    $email = $_POST['email'];
    $mdp = $_POST['mdp'];

    if ($email == "") {
        $message = "L'email est obligatoire.";
    } else if ($mdp == "") {
        $message = "Le mot de passe est obligatoire.";
    } else {
        $req = mysqli_prepare($conn, "SELECT id, prenom, mdp FROM users WHERE mail = ?");
        mysqli_stmt_bind_param($req, "s", $email);
        mysqli_stmt_execute($req);
        $resultat = mysqli_stmt_get_result($req);
        $utilisateur = mysqli_fetch_assoc($resultat);

        if (!$utilisateur) {
            $message = "Cet email n'existe pas.";
        } else {
            if (password_verify($mdp, $utilisateur['mdp'])) {
                $_SESSION['id_user'] = $utilisateur['id'];
                $_SESSION['prenom'] = $utilisateur['prenom'];
                header("Location: ../index.php");
                exit();
            } else {
                $message = "Mot de passe incorrect.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion - Fleexi</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

    <header>
        <h1>Fleexi</h1>
        <nav>
            <a href="../index.php">Accueil</a>
            <a href="inscription.php">Inscription</a>
        </nav>
    </header>

    <div class="contenu">
        <h2>Connexion</h2>

        <?php if ($message != "") { ?>
            <div class="message"><?php echo $message; ?></div>
        <?php } ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email">
            <input type="password" name="mdp" placeholder="Mot de passe">
            <button type="submit" name="connecter" class="bouton">Se connecter</button>
        </form>
    </div>

</body>

</html>