<?php
session_start();
require "../config/config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: ../index.php");
    exit();
}
require "header.php";


// Récupérer les tickets vendus par service pour le mois en cours
$chart_query = "
    SELECT 
        s.nom_service,
        SUM(a.tv) AS tickets_vendus
    FROM affectations a
    JOIN services s ON a.services_id_service = s.id_service
    WHERE MONTH(a.ddate) = MONTH(CURRENT_DATE()) AND YEAR(a.ddate) = YEAR(CURRENT_DATE())
    GROUP BY s.id_service
    ORDER BY tickets_vendus DESC
";
$chart_result = $conn->query($chart_query);

$service_labels = [];
$service_values = [];
while ($row = $chart_result->fetch_assoc()) {
    $service_labels[] = $row['nom_service'];
    $service_values[] = $row['tickets_vendus'] ?? 0;
}
// Obtenir le mois et l'année en français
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Pour affichage en français
$mois_annee = date('F Y');
?>
<script src="../js/demo/chart.js"></script>

<div class="container mt-5">
    
    <h4 class="text-center mb-3">Utilisation des services (tickets vendus, <?php echo ucfirst($mois_annee); ?>)</h4>
    <canvas id="servicesChart" height="100"></canvas>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('servicesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($service_labels); ?>,
            datasets: [{
                label: 'Tickets vendus',
                data: <?php echo json_encode($service_values); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'x',
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Tickets vendus' }
                },
                x: {
                    title: { display: true, text: 'Service' }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>


<?php 
require "footer.php";
?>