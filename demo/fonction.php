<?php
session_start();
require "../config/config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: login.php");
    exit();
}

// Initialisation des variables pour la modification
$id_fonction_edit = "";
$nom_fonction_edit = "";

// Ajouter une nouvelle fonction
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_fonction'])) {
    $id_fonction = mysqli_real_escape_string($conn, $_POST['id_fonction']);
    $nom_fonction = mysqli_real_escape_string($conn, $_POST['nom_fonction']);

    if (!empty($id_fonction) && !empty($nom_fonction)) {
        $check_query = "SELECT * FROM fonction WHERE id = '$id_fonction'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) == 0) {
            $insert_query = "INSERT INTO fonction (id, fonction) VALUES ('$id_fonction', '$nom_fonction')";
            if (mysqli_query($conn, $insert_query)) {
                echo "<div class='alert alert-success'>Nouvelle fonction ajoutée avec succès.</div>";
            } else {
                echo "<div class='alert alert-danger'>Erreur : " . mysqli_error($conn) . "</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Cet ID de fonction existe déjà.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Veuillez remplir tous les champs.</div>";
    }
}

// Suppression d'une fonction
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer_fonction'])) {
    $id_supprimer = mysqli_real_escape_string($conn, $_POST['id_supprimer']);
    $delete_query = "DELETE FROM fonction WHERE id = '$id_supprimer'";
    if (mysqli_query($conn, $delete_query)) {
        echo "<div class='alert alert-success'>Fonction supprimée avec succès.</div>";
    } else {
        echo "<div class='alert alert-danger'>Erreur : " . mysqli_error($conn) . "</div>";
    }
}
// Préparer la modification d'une fonction
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['modifier_fonction'])) {
    $id_modifier = mysqli_real_escape_string($conn, $_GET['modifier_fonction']);
    $edit_query = "SELECT * FROM fonction WHERE id = '$id_modifier'";
    $edit_result = mysqli_query($conn, $edit_query);
    if ($edit_row = mysqli_fetch_assoc($edit_result)) {
        $id_fonction_edit = $edit_row['id'];
        $nom_fonction_edit = $edit_row['fonction'];
    }
}

// Mettre à jour une fonction
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_fonction'])) {
    $id_modif = mysqli_real_escape_string($conn, $_POST['id_fonction']);
    $nom_modif = mysqli_real_escape_string($conn, $_POST['nom_fonction']);

    if (!empty($id_modif) && !empty($nom_modif)) {
        $update_query = "UPDATE fonction SET fonction = '$nom_modif' WHERE id = '$id_modif'";
        if (mysqli_query($conn, $update_query)) {
            echo "<div class='alert alert-success'>Fonction modifiée avec succès.</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur : " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Veuillez remplir tous les champs.</div>";
    }
}

// Récupérer toutes les fonctions enregistrées
$fonctions_query = "SELECT * FROM fonction";
$fonctions_result = mysqli_query($conn, $fonctions_query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <title>Gestion des Fonctions</title>
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <!-- Formulaire d'ajout/modification -->
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3><?php echo empty($id_fonction_edit) ? "Ajouter une Fonction" : "Modifier une Fonction"; ?></h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="id_fonction" class="form-label">ID de la Fonction</label>
                            <input type="text" name="id_fonction" id="id_fonction" class="form-control" 
                                   value="<?php echo htmlspecialchars($id_fonction_edit); ?>" 
                                   placeholder="Entrez l'ID de la fonction" required <?php echo !empty($id_fonction_edit) ? "readonly" : ""; ?>>
                        </div>
                        <div class="mb-3">
                            <label for="nom_fonction" class="form-label">Nom de la Fonction</label>
                            <input type="text" name="nom_fonction" id="nom_fonction" class="form-control" 
                                   value="<?php echo htmlspecialchars($nom_fonction_edit); ?>" 
                                   placeholder="Entrez le nom de la fonction" required>
                        </div>
                        <?php if (empty($id_fonction_edit)): ?>
                            <button type="submit" name="ajouter_fonction" class="btn btn-success">Ajouter</button>
                        <?php else: ?>
                            <button type="submit" name="modifier_fonction" class="btn btn-warning">Modifier</button>
                            <a href="fonction.php" class="btn btn-secondary">Annuler</a>
                        <?php endif; ?>
                        <a href="index.php" class="btn btn-secondary">Annuler</a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tableau des fonctions à droite -->
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-info text-white text-center">
                    <h3>Fonctions enregistrées</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom de la Fonction</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($fonction = mysqli_fetch_assoc($fonctions_result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($fonction['id']); ?></td>
                                    <td><?php echo htmlspecialchars($fonction['fonction']); ?></td>
                                    <td>
                                        <a href="?modifier_fonction=<?php echo $fonction['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="id_supprimer" value="<?php echo $fonction['id']; ?>">
                                            <button type="submit" name="supprimer_fonction" class="btn btn-danger btn-sm">Supprimer</button>
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

<?php
mysqli_close($conn);
?>
