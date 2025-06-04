<?php
session_start();
require __DIR__ . '/../../config/config.php';


// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: ../login.php");
    exit();
}

// Vérifier si un ID est passé dans l'URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Préparer la requête pour supprimer l'affectation
    $stmt = $conn->prepare("DELETE FROM affectations WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Rediriger vers la page des affectations avec un message de succès
        $_SESSION['message'] = "L'affectation a été supprimée avec succès.";
        $_SESSION['message_type'] = "success";
    } else {
        // Rediriger avec un message d'erreur
        $_SESSION['message'] = "Erreur lors de la suppression de l'affectation.";
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
} else {
    // Rediriger avec un message d'erreur si l'ID est invalide
    $_SESSION['message'] = "ID invalide.";
    $_SESSION['message_type'] = "warning";
}

// Rediriger vers la page des affectations
header("Location: ../affectations_list.php");
exit();
?>