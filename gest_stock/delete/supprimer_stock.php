<?php
session_start();
require __DIR__ . '/../../config/config.php';


// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: ../index.php");
    exit();
}

// Vérifier si l'ID du stock est passé en paramètre
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Préparer la requête pour supprimer l'élément du stock
    $sql = "DELETE FROM stock WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Rediriger vers la page de gestion du stock avec un message de succès
        $_SESSION['message'] = "L'élément a été supprimé avec succès.";
        header("Location: ../stock.php");
        exit();
    } else {
        // Rediriger vers la page de gestion du stock avec un message d'erreur
        $_SESSION['message'] = "Erreur lors de la suppression de l'élément.";
        header("Location: ../stock.php");
        exit();
    }
} else {
    // Rediriger vers la page de gestion du stock si l'ID n'est pas fourni
    $_SESSION['message'] = "ID d'élément non fourni.";
    header("Location: ../stock.php");
    exit();
}
?>