<?php
session_start();
require "../config/config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: login.php");
    exit();
}

// Initialisation des variables pour la modification
$id_role_edit = "";
$nom_role_edit = "";

// Ajouter un nouveau rôle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_role'])) {
    $id_role = mysqli_real_escape_string($conn, $_POST['id_role']);
    $nom_role = mysqli_real_escape_string($conn, $_POST['nom_role']);

    if (!empty($id_role) && !empty($nom_role)) {
        $check_query = "SELECT * FROM role WHERE id = '$id_role'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) == 0) {
            $insert_query = "INSERT INTO role (id, role) VALUES ('$id_role', '$nom_role')";
            if (mysqli_query($conn, $insert_query)) {
                echo "<div class='alert alert-success'>Nouveau rôle ajouté avec succès.</div>";
            } else {
                echo "<div class='alert alert-danger'>Erreur : " . mysqli_error($conn) . "</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Cet ID de rôle existe déjà.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Veuillez remplir tous les champs.</div>";
    }
}

// Suppression d'un rôle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer_role'])) {
    $id_supprimer = mysqli_real_escape_string($conn, $_POST['id_supprimer']);
    $delete_query = "DELETE FROM role WHERE id = '$id_supprimer'";
    if (mysqli_query($conn, $delete_query)) {
        echo "<div class='alert alert-success'>Rôle supprimé avec succès.</div>";
    } else {
        echo "<div class='alert alert-danger'>Erreur : " . mysqli_error($conn) . "</div>";
    }
}

// Préparer la modification d'un rôle
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['modifier_role'])) {
    $id_modifier = mysqli_real_escape_string($conn, $_GET['modifier_role']);
    $edit_query = "SELECT * FROM role WHERE id = '$id_modifier'";
    $edit_result = mysqli_query($conn, $edit_query);
    if ($edit_row = mysqli_fetch_assoc($edit_result)) {
        $id_role_edit = $edit_row['id'];
        $nom_role_edit = $edit_row['role'];
    }
}

// Mettre à jour un rôle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_role'])) {
    $id_modif = mysqli_real_escape_string($conn, $_POST['id_role']);
    $nom_modif = mysqli_real_escape_string($conn, $_POST['nom_role']);

    if (!empty($id_modif) && !empty($nom_modif)) {
        $update_query = "UPDATE role SET role = '$nom_modif' WHERE id = '$id_modif'";
        if (mysqli_query($conn, $update_query)) {
            echo "<div class='alert alert-success'>Rôle modifié avec succès.</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur : " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Veuillez remplir tous les champs.</div>";
    }
}

// Récupérer tous les rôles enregistrés
$roles_query = "SELECT * FROM role";
$roles_result = mysqli_query($conn, $roles_query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
    <title>Gestion des Rôles</title>
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <!-- Formulaire d'ajout/modification -->
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3><?php echo empty($id_role_edit) ? "Ajouter un Rôle" : "Modifier un Rôle"; ?></h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="id_role" class="form-label">ID du Rôle</label>
                            <input type="text" name="id_role" id="id_role" class="form-control" 
                                   value="<?php echo htmlspecialchars($id_role_edit); ?>" 
                                   placeholder="Entrez l'ID du rôle" required <?php echo !empty($id_role_edit) ? "readonly" : ""; ?>>
                        </div>
                        <div class="mb-3">
                            <label for="nom_role" class="form-label">Nom du Rôle</label>
                            <input type="text" name="nom_role" id="nom_role" class="form-control" 
                                   value="<?php echo htmlspecialchars($nom_role_edit); ?>" 
                                   placeholder="Entrez le nom du rôle" required>
                        </div>
                        <?php if (empty($id_role_edit)): ?>
                            <button type="submit" name="ajouter_role" class="btn btn-success">Ajouter</button>
                        <?php else: ?>
                            <button type="submit" name="modifier_role" class="btn btn-warning">Modifier</button>
                            <a href="role.php" class="btn btn-secondary">Annuler</a>
                        <?php endif; ?>
                        <a href="index.php" class="btn btn-secondary">Annuler</a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tableau des rôles à droite -->
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-info text-white text-center">
                    <h3>Rôles enregistrés</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom du Rôle</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($role = mysqli_fetch_assoc($roles_result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($role['id']); ?></td>
                                    <td><?php echo htmlspecialchars($role['role']); ?></td>
                                    <td>
                                        <a href="?modifier_role=<?php echo $role['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="id_supprimer" value="<?php echo $role['id']; ?>">
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
