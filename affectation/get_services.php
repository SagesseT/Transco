<?php
require "../config/config.php";

if (isset($_GET['ligne_id'])) {
    $ligne_id = intval($_GET['ligne_id']);

    // Récupérer les services associés à la ligne spécifiée
    $query = "SELECT id_service, nom_service FROM services WHERE id_ligne = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $ligne_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si des services sont trouvés, les envoyer en JSON
    if ($result->num_rows > 0) {
        $services = [];
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
        echo json_encode($services);
    } else {
        echo json_encode([]); // Aucun service trouvé
    }

    $stmt->close();
}
?>
