<?php
include "../config.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/connexion.php");
    exit();
}

$id_annonce = (int) $_GET['id'];
$id_user = $_SESSION['id_user'];

$req = mysqli_prepare($conn, "SELECT id FROM favoris WHERE id_user = ? AND id_annonce = ?");
mysqli_stmt_bind_param($req, "ii", $id_user, $id_annonce);
mysqli_stmt_execute($req);
mysqli_stmt_store_result($req);

if (mysqli_stmt_num_rows($req) > 0) {
    $req2 = mysqli_prepare($conn, "DELETE FROM favoris WHERE id_user = ? AND id_annonce = ?");
    mysqli_stmt_bind_param($req2, "ii", $id_user, $id_annonce);
    mysqli_stmt_execute($req2);
} else {
    $req2 = mysqli_prepare($conn, "INSERT INTO favoris (id_user, id_annonce) VALUES (?, ?)");
    mysqli_stmt_bind_param($req2, "ii", $id_user, $id_annonce);
    mysqli_stmt_execute($req2);
}

header("Location: mes_favoris.php");
exit();
?>
