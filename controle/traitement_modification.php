<?php
session_start();
require "../config/config.php";

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: ../index.php");
    exit();
}

function secure_input($data, $conn) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($conn, $data);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = secure_input($_POST['id'], $conn);
    $type_document = secure_input($_POST['type_document'], $conn);
    $type_mouvement = secure_input($_POST['type_mouvement'], $conn);
    $quantite = secure_input($_POST['quantite'], $conn);
    $destinataire = secure_input($_POST['destinataire'], $conn);
    $responsable = secure_input($_POST['responsable'], $conn);
    $commentaire = secure_input($_POST['commentaire'], $conn);

    // Récupère l'ancien mouvement
    $sql_old = "SELECT * FROM mouvements WHERE id = '$id'";
    $result_old = mysqli_query($conn, $sql_old);
    $old = mysqli_fetch_assoc($result_old);
    
    if (!$old) {
        $_SESSION['error'] = "Mouvement introuvable.";
        header("Location: mouvements.php");
        exit();
    }

    $stock_id = $old['stock_id'];

    // Met à jour le mouvement
    $sql = "UPDATE mouvements SET 
            type_document_id = '$type_document', 
            type_mouvement = '$type_mouvement',
            quantite = '$quantite',
            destinataire = '$destinataire',
            responsable = '$responsable',
            commentaire = '$commentaire'
            WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {

        // Met à jour le stock lié
        $quantite_stock = ($type_mouvement === 'sortie') ? -$quantite : $quantite;
        $emplacement = ($type_mouvement === 'sortie') ? "Sortie vers $destinataire" : "Entrée par $responsable";

        $sql_stock = "UPDATE stock SET 
                      type_document_id = '$type_document',
                      quantite = '$quantite_stock',
                      emplacement = '$emplacement'
                      WHERE id = '$stock_id'";
        mysqli_query($conn, $sql_stock);

        $_SESSION['success'] = "Mouvement et stock mis à jour avec succès.";
    } else {
        $_SESSION['error'] = "Erreur lors de la mise à jour : " . mysqli_error($conn);
    }

    header("Location: mouvements.php");
    exit();
}
?>