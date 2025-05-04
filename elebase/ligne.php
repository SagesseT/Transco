<?php
session_start();
require "../config/config.php";

// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: login.php");
    exit();
}

// Initialisation des variables
$id_ligne_edit = "";
$nom_ligne_edit = "";
$code_ligne_edit = "";

// Ajouter une ligne
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_ligne'])) {
    $nom_ligne = mysqli_real_escape_string($conn, $_POST['nom_ligne']);
    $code_ligne = mysqli_real_escape_string($conn, $_POST['code_ligne']);

    if (!empty($nom_ligne) && !empty($code_ligne)) {
        $check_query = "SELECT * FROM lignes WHERE nom_ligne = '$nom_ligne'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) == 0) {
            $insert_query = "INSERT INTO lignes (nom_ligne, code_ligne) VALUES ('$nom_ligne', '$code_ligne')";
            if (mysqli_query($conn, $insert_query)) {
                echo "<div class='alert alert-success'>Nouvelle ligne ajoutée avec succès.</div>";
            } else {
                echo "<div class='alert alert-danger'>Erreur : " . mysqli_error($conn) . "</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Cette ligne existe déjà.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Veuillez remplir tous les champs.</div>";
    }
}

// Supprimer une ligne
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer_ligne'])) {
    $id_ligne_supprimer = mysqli_real_escape_string($conn, $_POST['id_supprimer']);
    $delete_query = "DELETE FROM lignes WHERE id_ligne = '$id_ligne_supprimer'";
    if (mysqli_query($conn, $delete_query)) {
        echo "<div class='alert alert-success'>Ligne supprimée avec succès.</div>";
    } else {
        echo "<div class='alert alert-danger'>Erreur : " . mysqli_error($conn) . "</div>";
    }
}

// Préparation à la modification
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['modifier_ligne'])) {
    $id_modifier = mysqli_real_escape_string($conn, $_GET['modifier_ligne']);
    $edit_query = "SELECT * FROM lignes WHERE id_ligne = '$id_modifier'";
    $edit_result = mysqli_query($conn, $edit_query);
    if ($edit_row = mysqli_fetch_assoc($edit_result)) {
        $id_ligne_edit = $edit_row['id_ligne'];
        $nom_ligne_edit = $edit_row['nom_ligne'];
        $code_ligne_edit = $edit_row['code_ligne'];
    }
}

// Modifier une ligne
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_ligne'])) {
    $id_modif = mysqli_real_escape_string($conn, $_POST['id_ligne']);
    $nom_modif = mysqli_real_escape_string($conn, $_POST['nom_ligne']);
    $code_modif = mysqli_real_escape_string($conn, $_POST['code_ligne']);

    if (!empty($id_modif) && !empty($nom_modif) && !empty($code_modif)) {
        $update_query = "UPDATE lignes SET nom_ligne = '$nom_modif', code_ligne = '$code_modif' WHERE id_ligne = '$id_modif'";
        if (mysqli_query($conn, $update_query)) {
            echo "<div class='alert alert-success'>Ligne modifiée avec succès.</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur : " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Veuillez remplir tous les champs.</div>";
    }
}

// Récupération des lignes
$ligne_query = "SELECT * FROM lignes";
$ligne_result = mysqli_query($conn, $ligne_query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Lignes</title>
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
    <link rel="stylesheet" href="../css/styles.css"></head>
<body>

<div class="container mt-5">
    <div class="row">
        <!-- Formulaire -->
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h3><?php echo empty($id_ligne_edit) ? "Ajouter une Ligne" : "Modifier une Ligne"; ?></h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="nom_ligne" class="form-label">Nom de ligne</label>
                            <input type="text" name="nom_ligne" id="nom_ligne" class="form-control"
                                   value="<?php echo htmlspecialchars($nom_ligne_edit); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="code_ligne" class="form-label">Code ligne</label>
                            <input type="text" name="code_ligne" id="code_ligne" class="form-control"
                                   value="<?php echo htmlspecialchars($code_ligne_edit); ?>" required>
                        </div>

                        <?php if (empty($id_ligne_edit)): ?>
                            <button type="submit" name="ajouter_ligne" class="btn btn-success">Ajouter</button>
                        <?php else: ?>
                            <input type="hidden" name="id_ligne" value="<?php echo htmlspecialchars($id_ligne_edit); ?>">
                            <button type="submit" name="modifier_ligne" class="btn btn-warning">Modifier</button>
                            <a href="ligne.php" class="btn btn-secondary">Annuler</a>
                        <?php endif; ?>

                        <a href="../accueil.php" class="btn btn-secondary float-end">Retour</a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tableau -->
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-info text-white text-center">
                    <h3>Lignes enregistrées</h3>
                </div>
                <div class="card-body">
                <table class="table table-bordered">
                <thead >
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Code</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($ligne = mysqli_fetch_assoc($ligne_result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($ligne['id_ligne']); ?></td>
                                    <td><?php echo htmlspecialchars($ligne['nom_ligne']); ?></td>
                                    <td><?php echo htmlspecialchars($ligne['code_ligne']); ?></td>
                                    <td class="text-center">
                                        <a href="?modifier_ligne=<?php echo $ligne['id_ligne']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="id_supprimer" value="<?php echo $ligne['id_ligne']; ?>">
                                            <?php
                                            /* ?> <button type="submit" name="supprimer_ligne" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button> */
                                            ?>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php mysqli_close($conn); ?>
