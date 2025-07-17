<?php
session_start();
require "../config/config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: ../index.php");
    exit();
}
require "header.php";

// Récupérer les statistiques de stock
$sql_stock = "SELECT td.nom, SUM(s.quantite) as total 
               FROM stock s 
               JOIN types_documents td ON s.type_document_id = td.id 
               GROUP BY td.nom";
$result_stock = mysqli_query($conn, $sql_stock);

// Récupérer les derniers mouvements
$sql_mouvements = "SELECT m.*, td.nom as document_nom 
                   FROM mouvements m 
                   JOIN types_documents td ON m.type_document_id = td.id 
                   ORDER BY m.date_mouvement DESC 
                   LIMIT 5";
$result_mouvements = mysqli_query($conn, $sql_mouvements);
?>

<div class="container mt-4">
    <div class="row">
        <!-- Stock Statistics with Chart -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5>Statistiques de Stock</h5>
                </div>
                <div class="card-body">
                    <canvas id="stockChart" class="mb-4"></canvas>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Type de Document</th>
                                    <th>Quantité en Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $stock_data = [];
                                while ($row = mysqli_fetch_assoc($result_stock)): 
                                    $stock_data[] = $row;
                                ?>
                                    <tr>
                                        <td><?php echo $row['nom']; ?></td>
                                        <td><?php echo $row['total']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Movements -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5>Derniers Mouvements</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php while ($row = mysqli_fetch_assoc($result_mouvements)): ?>
                            <li class="list-group-item">
                                <strong><?php echo $row['document_nom']; ?></strong><br>
                                <?php echo $row['type_mouvement'] == 'entree' ? 'Entrée' : 'Sortie'; ?>: 
                                <?php echo $row['quantite']; ?> paquets<br>
                                <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($row['date_mouvement'])); ?></small>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>-
</div>

<script src="../js/demo/chart.js"></script>
<script>
    // Prepare data for the stock chart
    const stockData = <?php echo json_encode($stock_data); ?>;
    const labels = stockData.map(item => item.nom);
    const data = stockData.map(item => item.total);

    // Render the stock chart
    const ctx = document.getElementById('stockChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Quantité en Stock',
                data: data,
                backgroundColor: 'rgba(30, 2, 156, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php require "footer.php"; ?>