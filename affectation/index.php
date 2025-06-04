<?php
session_start();
require "../config/config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: ../index.php");
    exit();
}
require "header.php";


// Fonctions utilitaires
function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}
// Récupérer la liste des mouvements
// Requête pour récupérer les données de la table 'affectations' triées par date
$requete = "SELECT * FROM affectations ORDER BY ddate DESC"; // Tri par date décroissante
$resultat = $conn->query($requete);

$stmt = $conn->prepare("SELECT * FROM affectations WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

?>
<form method="get" class="mb-3">
    <div class="input-group">
        <input type="date" class="form-control" name="date_filter" value="<?php echo isset($_GET['date_filter']) ? htmlspecialchars($_GET['date_filter']) : date('Y-m-d'); ?>">
        <button type="submit" class="btn btn-primary">Voir</button>
    </div>
</form>

<?php
// Get the selected date from the form
$date_filter = isset($_GET['date_filter']) ? $_GET['date_filter'] : date('Y-m-d');

// Fetch grouped data by lignes, services, and fr, filtered by date using prepared statements
$query = "
    SELECT 
        l.code_ligne AS lignes,
        s.nom_service AS services,
        a.fr AS fr, 
        SUM(a.total_tickets) AS total_tickets,
        SUM(a.tr) AS tr,
        SUM(a.tv) AS tv
    FROM affectations a
    JOIN lignes l ON a.lignes_id_ligne = l.id_ligne
    JOIN services s ON a.services_id_service = s.id_service
    WHERE DATE(a.ddate) = ?
    GROUP BY l.code_ligne, s.nom_service, a.fr
    ORDER BY l.code_ligne, s.nom_service, a.fr
";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $date_filter);
$stmt->execute();
$result = $stmt->get_result();




// Initialize grand totals
$grand_total_donnes = 0;
$grand_total_rendus = 0;
$grand_total_vendus = 0;


if ($result->num_rows > 0) {
    echo '<div class="container mt-5">';
    echo '<h2 class="text-center">Résumé Journalier</h2>';
    echo '<div class="card">';
    echo '<div class="card-header bg-success text-white d-flex justify-content-between align-items-center">';
    echo '<h5 class="card-title">Résumé Journalier</h5>';
    echo '<div>';
    echo '<button class="btn btn-light btn-sm" onclick="printResume()">Imprimer</button>';
    echo '<button class="btn btn-light btn-sm" onclick="exportToExcel()">Exporter en Excel</button>';
    echo '</div>';
    echo '</div>';
    echo '<div class="card-body">';

    $current_ligne = '';
    $total_ligne_donnes = 0;
    $total_ligne_rendus = 0;
    $total_ligne_vendus = 0;

    // Initialize grand totals
    $grand_total_donnes = 0;
    $grand_total_rendus = 0;
    $grand_total_vendus = 0;

    while ($row = $result->fetch_assoc()) {
        // Check if the current row belongs to a new ligne
        if ($current_ligne !== $row['lignes']) {
            // Display totals for the previous ligne
            if ($current_ligne !== '') {
                echo '<tr class="table-secondary">';
                echo '<td colspan="2"><strong>Total Ligne ' . htmlspecialchars($current_ligne) . '</strong></td>';
                echo '<td>' . $total_ligne_donnes . '</td>';
                echo '<td>' . $total_ligne_rendus . '</td>';
                echo '<td>' . $total_ligne_vendus . '</td>';
                echo '</tr>';
                echo '</tbody>';
                echo '</table>';
                echo '</div>'; // Close table-responsive
            }

            // Add the previous ligne totals to the grand totals
            $grand_total_donnes += $total_ligne_donnes;
            $grand_total_rendus += $total_ligne_rendus;
            $grand_total_vendus += $total_ligne_vendus;

            // Reset totals for the new ligne
            $current_ligne = $row['lignes'];
            $total_ligne_donnes = 0;
            $total_ligne_rendus = 0;
            $total_ligne_vendus = 0;

            // Display the new ligne header
            echo '<h5 class="mt-4">LIGNE ' . htmlspecialchars($current_ligne) . '</h5>';
            echo '<div class="table-responsive">';
            echo '<table class="table table-bordered table-sm">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Service</th>';
            echo '<th>Feuille de Route</th>';
            echo '<th>Tickets Donnés</th>';
            echo '<th>Tickets Rendus</th>';
            echo '<th>Tickets Vendus</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
        }

        // Display the service row
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['services']) . '</td>';
        echo '<td>' . htmlspecialchars($row['fr']) . '</td>';
        echo '<td>' . htmlspecialchars($row['total_tickets']) . '</td>';
        echo '<td>' . htmlspecialchars($row['tr']) . '</td>';
        echo '<td>' . htmlspecialchars($row['tv']) . '</td>';
        echo '</tr>';

        // Update totals for the current ligne
        $total_ligne_donnes += $row['total_tickets'];
        $total_ligne_rendus += $row['tr'];
        $total_ligne_vendus += $row['tv'];
    }

    // Display totals for the last ligne
    echo '<tr class="total-row">';
    echo '<td colspan="2"><strong>Total Ligne ' . htmlspecialchars($current_ligne) . '</strong></td>';
    echo '<td>' . $total_ligne_donnes . '</td>';
    echo '<td>' . $total_ligne_rendus . '</td>';
    echo '<td>' . $total_ligne_vendus . '</td>';
    echo '</tr>';
    echo '</tbody>';
    echo '</table>';
    echo '</div>'; // Close table-responsive

    // Add the last ligne totals to the grand totals
    $grand_total_donnes += $total_ligne_donnes;
    $grand_total_rendus += $total_ligne_rendus;
    $grand_total_vendus += $total_ligne_vendus;

    // Display the grand totals as a separate section
    echo '<div class="table-responsive mt-3">';
    echo '<h5 class="text-center">Total Général</h5>';
    echo '<table class="table table-bordered table-sm">';
    echo '<thead>';
    echo '<tr class="table-primary">';
    echo '<th colspan="2">Description</th>';
    echo '<th>Tickets Donnés</th>';
    echo '<th>Tickets Rendus</th>';
    echo '<th>Tickets Vendus</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    echo '<tr class="table-secondary">';
    echo '<td colspan="2"><strong>Grand Total</strong></td>';
    echo '<td>' . $grand_total_donnes . '</td>';
    echo '<td>' . $grand_total_rendus . '</td>';
    echo '<td>' . $grand_total_vendus . '</td>';
    echo '</tr>';
    echo '</tbody>';
    echo '</table>';
    echo '</div>'; // Close grand totals section

    echo '</div>'; // Close card-body
    echo '</div>'; // Close card
    echo '</div>'; // Close container
} else {
    echo '<p class="text-center">Aucune affectation trouvée.</p>';
}
?>

<script>
    function printResume() {
        window.print();
    }

    function exportToExcel() {
        // Logic to export the table data to Excel
        // You can use libraries like SheetJS (xlsx) for this purpose
        alert("Fonction d'exportation vers Excel à implémenter.");
    }
</script>

<?php       
require "footer.php";
?>