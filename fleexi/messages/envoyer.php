<?php
include "../config.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/connexion.php");
    exit();
}

if (isset($_POST['envoyer'])) {

    $id_expediteur = $_SESSION['id_user'];
    $id_destinataire = (int) $_POST['id_destinataire'];
    $id_annonce = (int) $_POST['id_annonce'];
    $contenu = $_POST['contenu'];

    if ($contenu != "") {
        $req = mysqli_prepare($conn, "INSERT INTO messages (id_expediteur, id_destinataire, id_annonce, contenu) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($req, "iiis", $id_expediteur, $id_destinataire, $id_annonce, $contenu);
        mysqli_stmt_execute($req);
    }
}

header("Location: discussion.php?annonce=" . $id_annonce . "&autre=" . $id_destinataire);
exit();
?>
