<?php
session_start();
require "../config/config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: ../login.php");
    exit();
}

// Initialisation des variables pour la modification
$id_grade_edit = "";
$nom_grade_edit = "";

// Ajouter un nouveau grade
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_grade'])) {
    $id_grade = mysqli_real_escape_string($conn, $_POST['id_grade']);
    $nom_grade = mysqli_real_escape_string($conn, $_POST['nom_grade']);

    if (!empty($id_grade) && !empty($nom_grade)) {
        $check_query = "SELECT * FROM grade WHERE id = '$id_grade'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) == 0) {
            $insert_query = "INSERT INTO grade (id, grade) VALUES ('$id_grade', '$nom_grade')";
            if (mysqli_query($conn, $insert_query)) {
                echo "<div class='alert alert-success'>Nouveau grade ajouté avec succès.</div>";
            } else {
                echo "<div class='alert alert-danger'>Erreur : " . mysqli_error($conn) . "</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Cet ID de grade existe déjà.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Veuillez remplir tous les champs.</div>";
    }
}

// Suppression d'un grade
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer_grade'])) {
    $id_supprimer = mysqli_real_escape_string($conn, $_POST['id_supprimer']);
    $delete_query = "DELETE FROM grade WHERE id = '$id_supprimer'";
    if (mysqli_query($conn, $delete_query)) {
        echo "<div class='alert alert-success'>Grade supprimé avec succès.</div>";
    } else {
        echo "<div class='alert alert-danger'>Erreur : " . mysqli_error($conn) . "</div>";
    }
}

// Préparer la modification d'un grade
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['modifier_grade'])) {
    $id_modifier = mysqli_real_escape_string($conn, $_GET['modifier_grade']);
    $edit_query = "SELECT * FROM grade WHERE id = '$id_modifier'";
    $edit_result = mysqli_query($conn, $edit_query);
    if ($edit_row = mysqli_fetch_assoc($edit_result)) {
        $id_grade_edit = $edit_row['id'];
        $nom_grade_edit = $edit_row['grade'];
    }
}

// Mettre à jour un grade
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_grade'])) {
    $id_modif = mysqli_real_escape_string($conn, $_POST['id_grade']);
    $nom_modif = mysqli_real_escape_string($conn, $_POST['nom_grade']);

    if (!empty($id_modif) && !empty($nom_modif)) {
        $update_query = "UPDATE grade SET grade = '$nom_modif' WHERE id = '$id_modif'";
        if (mysqli_query($conn, $update_query)) {
            echo "<div class='alert alert-success'>Grade modifié avec succès.</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur : " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Veuillez remplir tous les champs.</div>";
    }
}

// Récupérer tous les grades enregistrés
$grades_query = "SELECT * FROM grade";
$grades_result = mysqli_query($conn, $grades_query);
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
        
    <title>Gestion des Grades</title>
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <!-- Formulaire d'ajout/modification -->
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3><?php echo empty($id_grade_edit) ? "Ajouter un Grade" : "Modifier un Grade"; ?></h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="id_grade" class="form-label">ID du Grade</label>
                            <input type="text" name="id_grade" id="id_grade" class="form-control" 
                                   value="<?php echo htmlspecialchars($id_grade_edit); ?>" 
                                   placeholder="Entrez l'ID du grade" required <?php echo !empty($id_grade_edit) ? "readonly" : ""; ?>>
                        </div>
                        <div class="mb-3">
                            <label for="nom_grade" class="form-label">Nom du Grade</label>
                            <input type="text" name="nom_grade" id="nom_grade" class="form-control" 
                                   value="<?php echo htmlspecialchars($nom_grade_edit); ?>" 
                                   placeholder="Entrez le nom du grade" required>
                        </div>
                        <?php if (empty($id_grade_edit)): ?>
                            <button type="submit" name="ajouter_grade" class="btn btn-success">Ajouter</button>
                        <?php else: ?>
                            <button type="submit" name="modifier_grade" class="btn btn-warning">Modifier</button>
                            <a href="grade.php" class="btn btn-secondary">Annuler</a>
                        <?php endif; ?>
                        <a href="index.php" class="btn btn-secondary">Annuler</a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tableau des grades à droite -->
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-info text-white text-center">
                    <h3>Grades enregistrés</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom du Grade</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($grade = mysqli_fetch_assoc($grades_result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($grade['id']); ?></td>
                                    <td><?php echo htmlspecialchars($grade['grade']); ?></td>
                                    <td>
                                        <a href="?modifier_grade=<?php echo $grade['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="id_supprimer" value="<?php echo $grade['id']; ?>">
                                            <button type="submit" name="supprimer_grade" class="btn btn-danger btn-sm">Supprimer</button>
                                            
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
