<?php
session_start();
require __DIR__ . '/../../config/config.php';
// Vérification de la connexion à la base de données
if (!$conn) {
    die("<div class='alert alert-danger'>Erreur de connexion à la base de données : " . mysqli_connect_error() . "</div>");
}



// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sécurisation des données
    function cleanInput($data) {
        return htmlspecialchars(trim($data));
    }

    $date = cleanInput($_POST['ddate']);
    $ligne = intval($_POST['ligne']);
    $service = intval($_POST['service']);
    $fr = cleanInput($_POST['fr']);
    $serie = cleanInput($_POST['serie']);
    $num_tickets_donner = intval($_POST['num_tickets_donner']);
    $num_tickets_retour = intval($_POST['num_tickets_retour']);
    $tr = intval($_POST['tr']);
    $total_tickets = intval($_POST['total_tickets']);
    $tv = intval($_POST['tv']);

    // Préparation de la requête
    $sql = "INSERT INTO affectations (
        lignes_id_ligne, services_id_service, fr, series, 
        num_tickets_donner, num_tickets_retour, tr, total_tickets, tv, ddate
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param(
            "iissiiiiis",
            $ligne, $service, $fr, $serie,
            $num_tickets_donner, $num_tickets_retour, $tr,
            $total_tickets, $tv, $date
        );

        if ($stmt->execute()) {
            // Redirection vers le formulaire après succès
            header("Location: ../ajouter_affectation.php?success=1");
            exit(); // Toujours ajouter exit après un header
        } else {
            echo "<div class='alert alert-danger'>❌ Erreur lors de l'enregistrement : " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>❌ Erreur de préparation de la requête : " . $conn->error . "</div>";
    }

    $conn->close();
} 

?>