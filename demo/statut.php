<?php
require "../config/config.php"; // Connexion à la base de données

// Nombre d'entrées par page
$limit = 30;

// Calculer l'offset de la page actuelle
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Requête principale avec pagination
$query = "SELECT id, matricule, compte_utilisateur, role_id, date_connexion, date_deconnexion,
                 IF(date_deconnexion IS NULL, '🟢 En ligne', '🔴 Hors ligne') AS statut
          FROM connexion 
          ORDER BY date_connexion DESC
          LIMIT $limit OFFSET $offset";

$result = $conn->query($query);

// Récupérer le nombre total d'entrées pour la pagination
$total_query = "SELECT COUNT(*) AS total FROM connexion";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_entries = $total_row['total'];

// Calculer le nombre total de pages
$total_pages = ceil($total_entries / $limit);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="../vendor/bootstrapc/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../vendor/aos/aos.css" rel="stylesheet">
    <link href="../vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="../vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Connexions des Utilisateurs</title>
</head>
<body class="bg-light">


<div class="container mt-5">
    <h2 class="text-center">📋 Liste des Connexions des Utilisateurs</h2>
    <a href="index.php" class="btn btn-secondary">Annuler</a>
    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Matricule</th>
                <th>Compte Utilisateur</th>
                <th>Rôle</th>
                <th>Date Connexion</th>
                <th>Statut</th>
                <th>Date Déconnexion</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row["id"]); ?></td>
                    <td><?php echo htmlspecialchars($row["matricule"]); ?></td>
                    <td><?php echo htmlspecialchars($row["compte_utilisateur"]); ?></td>
                    <td><?php echo htmlspecialchars($row["role_id"]); ?></td>
                    <td><?php echo htmlspecialchars($row["date_connexion"]); ?></td>
                    <td><?php echo $row["statut"]; ?></td>
                    <td><?php echo $row["date_deconnexion"] ? htmlspecialchars($row["date_deconnexion"]) : '—'; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1) { ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php } ?>
            
            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php } ?>

            <?php if ($page < $total_pages) { ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </nav>
</div>

</body>
</html>
