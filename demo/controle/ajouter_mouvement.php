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
    $type_mouvement = secure_input($_POST['type_mouvement'], $conn);
    $quantite = secure_input($_POST['quantite'], $conn);
    $reference = secure_input($_POST['reference'], $conn);
    $destinataire = secure_input($_POST['destinataire'], $conn);
    $responsable = secure_input($_POST['responsable'], $conn);
    $commentaire = secure_input($_POST['commentaire'], $conn);
    
    
    // Vérifier le stock pour les sorties
    if ($type_mouvement == 'sortie') {
        $sql_stock = "SELECT SUM(quantite) as total FROM stock WHERE type_document_id = '$type_document'";
        $result_stock = mysqli_query($conn, $sql_stock);
        $stock = mysqli_fetch_assoc($result_stock);
        
        if ($stock['total'] < $quantite) {
            $_SESSION['error'] = "Stock insuffisant pour cette sortie";
            header("Location: ../mouvements.php");
            exit();
        }
    }
    
    // Enregistrer le mouvement
    $sql = "INSERT INTO mouvements (type_document_id, type_mouvement, quantite, date_mouvement, responsable, destinataire, commentaire) 
            VALUES ('$type_document', '$type_mouvement', '$quantite', NOW(), '".$_SESSION['compte_utilisateur']."', '$destinataire', '$commentaire')";
    
    if (mysqli_query($conn, $sql)) {
        // Mettre à jour le stock
        if ($type_mouvement == 'entree') {
            $sql_stock = "INSERT INTO stock (type_document_id, quantite, date_entree, reference, emplacement) 
                          VALUES ('$type_document', '$quantite', NOW(), '$reference', 'Entrée par $responsable')";
        } else {
            // Pour les sorties, on doit gérer le stock existant (FIFO par exemple)
            // Ici, une implémentation simplifiée
            $sql_stock = "INSERT INTO stock (type_document_id, quantite, date_entree, reference, emplacement) 
                          VALUES ('$type_document', -$quantite,NOW(),  '$reference', 'Sortie vers $destinataire')";
        }
        mysqli_query($conn, $sql_stock);
        
        $_SESSION['success'] = "Mouvement enregistré avec succès";
    } else {
        $_SESSION['error'] = "Erreur lors de l'enregistrement: " . mysqli_error($conn);
    }
    
    header("Location: ../mouvements.php");
    exit();
}
?>