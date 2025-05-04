<?php
session_start();
require "../config/config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: ../index.php");
    exit();
}
require "header.php";


// Number of records per page
$records_per_page = 20;

// Get the current page from the URL, default to 1 if not set
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;

// Calculate the offset for the SQL query
$offset = ($current_page - 1) * $records_per_page;

// Get the total number of records
$total_records_query = "SELECT COUNT(*) AS total FROM affectations";
$total_records_result = $conn->query($total_records_query);
$total_records = $total_records_result->fetch_assoc()['total'];

// Calculate the total number of pages
$total_pages = ceil($total_records / $records_per_page);

// Fetch assignments with pagination
$query = "
    SELECT 
        a.id AS affectation_id,
        l.code_ligne AS ligne,
        s.nom_service AS service,
        a.fr,
        a.series,
        a.num_tickets_donner,
        a.num_tickets_retour,
        a.tr,
        a.total_tickets,
        a.tv,
        a.ddate
    FROM affectations a
    JOIN lignes l ON a.lignes_id_ligne = l.id_ligne
    JOIN services s ON a.services_id_service = s.id_service
    ORDER BY a.ddate DESC
    LIMIT $records_per_page OFFSET $offset
";
$result = $conn->query($query);
?>


    <h2 class="text-center">Liste des Affectations</h2>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title">Affectations Enregistrées</h5>
        </div>
        <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                    <thead>
                        <tr>
                           
                           <!-- <th>#</th> -->
                            <th>Ligne</th>
                            <th>Service</th>
                            <th>FR</th>
                            <th>Série</th>
                            <th>N.T. affec</th>
                            <th>N.T. Retour</th>
                            <th>Total Tickets</th>
                            <th>TR</th>
                            <th>TV</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <!-- <td><?php echo htmlspecialchars($row['affectation_id']); ?></td> -->
                                    <td><?php echo htmlspecialchars($row['ligne']); ?></td>
                                    <td><?php echo htmlspecialchars($row['service']); ?></td>
                                    <td><?php echo htmlspecialchars($row['fr']); ?></td>
                                    <td><?php echo htmlspecialchars($row['series']); ?></td>
                                    <td><?php echo htmlspecialchars($row['num_tickets_donner']); ?></td>
                                    <td><?php echo htmlspecialchars($row['num_tickets_retour']); ?></td>
                                    <td><?php echo htmlspecialchars($row['total_tickets']); ?></td>
                                    <td><?php echo htmlspecialchars($row['tr']); ?></td>
                                    <td><?php echo htmlspecialchars($row['tv']); ?></td>
                                    <td style="font-size: 11px;"><?php echo date('d/m/Y H:i', strtotime($row['ddate'])); ?></td>
                                    <td>
                                        <a href="modifier_affectation.php?id=<?php echo $row['affectation_id']; ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                                        <a href="delete/delete_affectation.php?id=<?php echo $row['affectation_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette affectation ?');"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">Aucune affectation trouvée.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                <nav>
                    <ul class="pagination justify-content-center">
                        <!-- Previous Page Link -->
                        <li class="page-item <?php if ($current_page <= 1) echo 'disabled'; ?>">
                            <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" tabindex="-1">Précédent</a>
                        </li>

                        <!-- Page Numbers -->
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php if ($i == $current_page) echo 'active'; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next Page Link -->
                        <li class="page-item <?php if ($current_page >= $total_pages) echo 'disabled'; ?>">
                            <a class="page-link" href="?page=<?php echo $current_page + 1; ?>">Suivant</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>


<?php require "footer.php"; ?>