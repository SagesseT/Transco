<?php
session_start();
require "../config/config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: ../index.php");
    exit();
}
require "header.php";?>


<?php 
require "footer.php";
?>