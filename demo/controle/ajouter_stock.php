<?php
session_start();
require __DIR__ . '/../../config/config.php';
// Define the secure_input function
function secure_input($data, $conn) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($conn, $data);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type_document = secure_input($_POST['type_document'], $conn);
    $quantite = secure_input($_POST['quantite'], $conn);
    $reference = secure_input($_POST['reference'], $conn);
    $destinataire = secure_input($_POST['destinataire'], $conn);
    $emplacement = secure_input($_POST['emplacement'], $conn);
    
    // Insérer dans la table stock
    $sql = "INSERT INTO stock (type_document_id, quantite, date_entree, reference, emplacement) 
            VALUES ('$type_document', '$quantite', NOW(), '$reference', '$emplacement')";
    
    if (mysqli_query($conn, $sql)) {
        // Enregistrer le mouvement d'entrée
        $sql_mouvement = "INSERT INTO mouvements (type_document_id, type_mouvement, quantite, date_mouvement, responsable, destinataire, commentaire) 
                          VALUES ('$type_document', 'entree', '$quantite', NOW(), '".$_SESSION['compte_utilisateur']."','$destinataire', 'Ajout initial au stock')";
        mysqli_query($conn, $sql_mouvement);
        
        $_SESSION['success'] = "Document ajouté au stock avec succès";
    } else {
        $_SESSION['error'] = "Erreur lors de l'ajout: " . mysqli_error($conn);
    }
    
    header("Location: ../stock.php");
    exit();
}
?>