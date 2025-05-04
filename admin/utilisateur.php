<?php
session_start();
require "../config/config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: ../login.php");
    exit();
}
// Handle search functionality
$search = '';
if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']);
}

$query = "SELECT * FROM utilisateur";
if (!empty($search)) {
    $query .= " WHERE nom LIKE '%$search%' OR postnom LIKE '%$search%' OR prenom LIKE '%$search%' OR compte_utilisateur LIKE '%$search%'";
}


$result = mysqli_query($conn, $query);
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
<a href="../accueil.php" class="btn btn-primary">Accueil</a>
    <div class="row">
        <!-- Formulaire d'ajout/modification -->
        
        <div class="container mt-3">
    <h4>Total Utilisateurs Enregistrés : <?php echo $total_users; ?></h4>
</div>
    </h2>

    <form method="post" class="mb-3">
    <div class="input-group" style="max-width: 700px;"> <!-- Réduction de la largeur -->
        <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primary">Rechercher</button>
        <button type="button" class="btn btn-secondary" onclick="document.querySelector('[name=search]').value=''">Annuler</button>
        <a type="button" class="btn btn-primary" href="ajouter.php" value=''>Ajouter un utilisateur</a>
    </div>
</form>


        <!-- Tableau des grades à droite -->
        
        <div class="container mt-5">
        
                    <h3>Liste des utilisateurs</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th>N°</th>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Postnom</th>
                            <th>Prénom</th>
                            <th style="font-size: 12px;">Compte Utilisateur</th>
                            <th style="font-size: 12px;">Mot de Passe</th>
                            <th>Fonction</th>
                            <th>Grade</th>
                            <th>Rôle</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['num']); ?></td>
                                    <td><?php echo htmlspecialchars($row['matricule']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($row['postnom']); ?></td>
                                    <td><?php echo htmlspecialchars($row['prenom']); ?></td>
                                    <td><?php echo htmlspecialchars($row['compte_utilisateur']); ?></td>
                                    <td class="text-truncate" style="max-width:200px;"> <?php echo htmlspecialchars($row['mot_de_passe']); ?></td>
                                    <td><?php echo htmlspecialchars($row['fonction_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['grade_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['role_id']); ?></td>
                                    <td style="font-size: 9px;"><?php echo htmlspecialchars($row['date']); ?></td>
                                    <td class="text-truncate" style="max-width:200px;">
                                    <a class="btn btn-warning btn-sm" href="modif.php?matricule=<?php echo $row['matricule']; ?> &nom=<?php echo $row['nom']; ?>  "> <i class="bi bi-pencil"></i></a>
                                        
                                        <a class="btn btn-danger btn-sm" href="delete_user.php?num=<?php echo $row['num']; ?> &nom=<?php echo $row['nom']; ?>   " onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');" ;>  Suprimer</a>   

                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="12" class="text-center">Aucun utilisateur trouvé</td>
                            </tr>
                        <?php endif; ?>
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
