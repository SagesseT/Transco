<?php
session_start();
require "../config/config.php"; // Connexion à la base de données

if (isset($_SESSION["user_id"])) {
    $matricule = $_SESSION["user_id"];
    
    // Mettre à jour la date_deconnexion pour l'utilisateur connecté
    $sql = "UPDATE connexion SET date_deconnexion = NOW() WHERE matricule = ? AND date_deconnexion IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matricule);
    $stmt->execute();
    $stmt->close();
    
    // Détruire la session
    session_destroy();
}

// Redirection vers la page de connexion
header("Location: ../login.php");
exit();
?>
