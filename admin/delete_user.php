<?php
session_start();
require "../config/config.php";

// Vérifier si le fichier est supprimé
if (!file_exists(__FILE__)) {
    header("Location: ../admin.php");
    exit();
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: ../login.php");
    exit();
}

// Vérifier si l'ID de l'utilisateur est fourni
if (!isset($_GET['num'])) {
    echo "<div class='alert alert-danger'>Erreur : Aucun utilisateur sélectionné pour suppression.</div>";
    exit();
}

$num = mysqli_real_escape_string($conn, $_GET['num']);

// Supprimer l'utilisateur de la base de données
$delete_query = "DELETE FROM utilisateur WHERE num = '$num'";

if (mysqli_query($conn, $delete_query)) {
    echo "<div class='alert alert-success'>Utilisateur supprimé avec succès.</div>";
    header("Location: utilisateur.php");
    exit();
} else {
    echo "<div class='alert alert-danger'>Erreur lors de la suppression de l'utilisateur : " . mysqli_error($conn) . "</div>";
}
?>