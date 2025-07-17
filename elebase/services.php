<?php
session_start();
require "../config/config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: ../login.php");
    exit();
}

// Initialisation des variables pour la modification
$id_service_edit = "";
$id_ligne_edit = "";
$code_service_edit = "";
$nom_service_edit = "";
// Pagination
$limit = 25;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Ajouter un nouveau service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_service'])) {
    $id_ligne = mysqli_real_escape_string($conn, $_POST['id_ligne']);
    $code_service = mysqli_real_escape_string($conn, $_POST['code_service']);
    $nom_service = mysqli_real_escape_string($conn, $_POST['nom_service']);

    if (!empty($id_ligne) && !empty($code_service) && !empty($nom_service)) {
        // Vérifier si le même code_service existe déjà pour cette ligne
        $check_query = "SELECT * FROM services WHERE id_ligne = '$id_ligne' AND code_service = '$code_service'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) == 0) {
            $insert_query = "INSERT INTO services (id_ligne, code_service, nom_service) VALUES ('$id_ligne', '$code_service', '$nom_service')";
            if (mysqli_query($conn, $insert_query)) {
                echo "<div class='alert alert-success'>Nouveau service ajouté avec succès.</div>";
            } else {
                echo "<div class='alert alert-danger'>Erreur : " . mysqli_error($conn) . "</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Ce code service existe déjà pour cette ligne.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Veuillez remplir tous les champs.</div>";
    }
}

// Suppression d'un service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer_service'])) {
    $id_supprimer = mysqli_real_escape_string($conn, $_POST['id_supprimer']);
    $delete_query = "DELETE FROM services WHERE id_service = '$id_supprimer'";
    if (mysqli_query($conn, $delete_query)) {
        echo "<div class='alert alert-success'>Service supprimé avec succès.</div>";
    } else {
        echo "<div class='alert alert-danger'>Erreur : " . mysqli_error($conn) . "</div>";
    }
}



// Préparer la modification d’un service
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['modifier_service'])) {
    $id_modifier = mysqli_real_escape_string($conn, $_GET['modifier_service']);
    $edit_query = "SELECT * FROM services WHERE id_service = '$id_modifier'";
    $edit_result = mysqli_query($conn, $edit_query);
    if ($edit_row = mysqli_fetch_assoc($edit_result)) {
        $id_service_edit = $edit_row['id_service'];
        $id_ligne_edit = $edit_row['id_ligne'];
        $code_service_edit = $edit_row['code_service'];
        $nom_service_edit = $edit_row['nom_service'];
    }
}

// Mettre à jour un service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_service'])) {
    $id_modif = mysqli_real_escape_string($conn, $_POST['id_service']);
    $id_ligne_modif = mysqli_real_escape_string($conn, $_POST['id_ligne']);
    $code_modif = mysqli_real_escape_string($conn, $_POST['code_service']);
    $nom_modif = mysqli_real_escape_string($conn, $_POST['nom_service']);

    if (!empty($id_modif) && !empty($nom_modif) && !empty($code_modif) && !empty($id_ligne_modif)) {
        $update_query = "UPDATE services SET id_ligne = '$id_ligne_modif', code_service = '$code_modif', nom_service = '$nom_modif' WHERE id_service = '$id_modif'";
        if (mysqli_query($conn, $update_query)) {
            echo "<div class='alert alert-success'>Service modifié avec succès.</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur : " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Veuillez remplir tous les champs.</div>";
    }
}
// Nombre total de services
$total_query = "SELECT COUNT(*) as total FROM services";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_services = $total_row['total'];
$total_pages = ceil($total_services / $limit);

// Récupérer les services paginés
$services_query = "SELECT * FROM services LIMIT $limit OFFSET $offset";
$services_result = mysqli_query($conn, $services_query);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/aos.css">
    <link rel="stylesheet" href="../css/glightbox.min.css">
    <link rel="stylesheet" href="../css/swiper-bundle.min.css">
    <link href="../vendor/bootstrapc/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../vendor/aos/aos.css" rel="stylesheet">
    <link href="../vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="../vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="shortcut icon" href="../img/transco.png" type="image/x-icon">
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <!-- Formulaire d'ajout/modification -->
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3><?php echo empty($id_service_edit) ? "Ajouter un Service" : "Modifier un Service"; ?></h3>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <input type="hidden" name="id_service" value="<?php echo $id_service_edit; ?>">
                        <div class="mb-3">
                            <label for="id_ligne" class="form-label">Ligne</label>
                            <select class="form-select" name="id_ligne" required>
                                <option value="">Sélectionner une ligne</option>
                                <?php
                                $sql = "SELECT * FROM lignes ORDER BY code_ligne";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $selected = ($row['id_ligne'] == $id_ligne_edit) ? "selected" : "";
                                    echo "<option value='{$row['id_ligne']}' $selected>{$row['code_ligne']} - {$row['nom_ligne']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="code_service" class="form-label">Code Service</label>
                            <input type="text" class="form-control" name="code_service" value="<?php echo $code_service_edit; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="nom_service" class="form-label">Nom Service</label>
                            <input type="text" class="form-control" name="nom_service" value="<?php echo $nom_service_edit; ?>" required>
                        </div>
                        <button type="submit" name="<?php echo empty($id_service_edit) ? 'ajouter_service' : 'modifier_service'; ?>" class="btn btn-primary">
                            <?php echo empty($id_service_edit) ? 'Ajouter' : 'Modifier'; ?>
                        </button>
                        <a href="../accueil.php" class="btn btn-secondary">Retour</a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tableau des services -->
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-info text-white text-center">
                    <h3>Services enregistrés</h3>
                </div>
                <div class="card-body">
                <table class="table table-bordered table-striped mt-3">
                <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Id ligne</th>
                                <th>Code Service</th>
                                <th>Nom service</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($service = mysqli_fetch_assoc($services_result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($service['id_service']); ?></td>
                                    <td><?php echo htmlspecialchars($service['id_ligne']); ?></td>
                                    <td><?php echo htmlspecialchars($service['code_service']); ?></td>
                                    <td><?php echo htmlspecialchars($service['nom_service']); ?></td>
                                    <td class="text-center">

                                        <a href="?modifier_service=<?php echo $service['id_service']; ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                                        <form method="POST" class="d-inline">
                                            <?php
                                            /*?> <input type="hidden" name="id_supprimer" value="<?php echo $service['id_service']; ?>"> */
                                            ?>
                                            </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <?php if ($total_pages > 1): ?>
                    <nav>
                        <ul class="pagination justify-content-center mt-3">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php mysqli_close($conn); ?>
