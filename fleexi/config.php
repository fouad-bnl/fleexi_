<?php
session_start();

$conn = mysqli_connect("localhost", "root", "root", "fleexi");

if (!$conn) {
    die("Connexion a la base de donnees echouee : " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");
?>
