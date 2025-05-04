<?php
session_start();
require "../config/config.php";


// Vérifier si l'utilisateur est connecté 



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matricule'], $_POST['nom'], $_POST['postnom'], $_POST['prenom'], $_POST['compte_utilisateur'], $_POST['mot_de_passe'], $_POST['fonction_id'], $_POST['grade_id'], $_POST['role_id'])) {
    $matricule = mysqli_real_escape_string($conn, $_POST['matricule']);
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $postnom = mysqli_real_escape_string($conn, $_POST['postnom']);
    $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
    $compte_utilisateur = mysqli_real_escape_string($conn, $_POST['compte_utilisateur']);
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT); //  password hashé
    $fonction_id = mysqli_real_escape_string($conn, $_POST['fonction_id']);
    $grade_id = mysqli_real_escape_string($conn, $_POST['grade_id']);
    $role_id = mysqli_real_escape_string($conn, $_POST['role_id']);

    

    // Vérifier si le matricule ou le compte utilisateur existent déjà
    $check_query = "SELECT * FROM utilisateur WHERE matricule = '$matricule' OR compte_utilisateur = '$compte_utilisateur'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $existing_user = mysqli_fetch_assoc($check_result);
        
        if ($existing_user['matricule'] === $matricule) {
            echo "<div class='alert alert-danger'>Erreur : Le matricule '$matricule' existe déjà.</div>";
        } elseif ($existing_user['compte_utilisateur'] === $compte_utilisateur) {
            echo "<div class='alert alert-danger'>Erreur : Le compte utilisateur '$compte_utilisateur' existe déjà.</div>";
        }
    } else {
        // Insérer le nouvel utilisateur
        $insert_query = "INSERT INTO utilisateur (matricule, nom, postnom, prenom, compte_utilisateur, mot_de_passe, fonction_id, grade_id, role_id) 
                         VALUES ('$matricule', '$nom', '$postnom', '$prenom', '$compte_utilisateur', '$mot_de_passe', '$fonction_id', '$grade_id', '$role_id')";

        if (mysqli_query($conn, $insert_query)) {
            echo "<div class='alert alert-success'>Utilisateur enregistré avec succès.</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur lors de l'ajout de l'utilisateur : " . mysqli_error($conn) . "</div>";
        }
    }
}
require "../config/role.php";
?>

<link href="js/admin.js" rel="stylesheet">

<div class="container ">
    
<?php 
// Fetch options for fonction, grade, and role
$fonctions = mysqli_query($conn, "SELECT * FROM fonction");
$grades = mysqli_query($conn, "SELECT * FROM grade");
$roles = mysqli_query($conn, "SELECT * FROM role");
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
        
    <title>Ajouter un utilisateur</title>
</head>
<body>




<div class="container mt-5">
<a href="utilisateur.php" class="btn btn-secondary">Annuler</a>
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center">
            <h3>Ajouter un utilisateur</h3>
        </div>
        <div class="card-body">
    <form method="post" action="ajouter.php">
        <div class="mb-3">
            <label for="matricule" class="form-label">Matricule</label>
            <input type="number"  name="matricule" id="matricule" class="form-control w-50" min="100" size="20" required>
        </div>
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" id="nom" class="form-control w-50" required>
        </div>
        <div class="mb-3">
            <label for="postnom" class="form-label">Postnom</label>
            <input type="text" name="postnom" id="postnom" class="form-control w-50" required>
        </div>
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" name="prenom" id="prenom" class="form-control w-50" required>
        </div>
        <div class="mb-3">
            <label for="compte_utilisateur" class="form-label">Compte Utilisateur</label>
            <input type="text" name="compte_utilisateur" id="compte_utilisateur" class="form-control w-50" required>
        </div>
        <div class="mb-3">
            <label for="mot_de_passe" class="form-label">Mot de Passe</label>
            <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control w-50" required>
        </div>
        <div class="mb-3">
            <label for="fonction_id" class="form-label">Fonction</label>
            <select name="fonction_id" id="fonction_id" class="form-control w-50" required>
                <option value="">Sélectionner une fonction</option>
                <?php while ($fonction = mysqli_fetch_assoc($fonctions)): ?>
                    <option value="<?php echo $fonction['id']; ?>"><?php echo htmlspecialchars($fonction['fonction']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="grade_id" class="form-label">Grade</label>
            <select name="grade_id" id="grade_id" class="form-control w-50" required>
                <option value="">Sélectionner un grade</option>
                <?php while ($grade = mysqli_fetch_assoc($grades)): ?>
                    <option value="<?php echo $grade['id']; ?>"><?php echo htmlspecialchars($grade['grade']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="role_id" class="form-label">Role</label>
            <select name="role_id" id="role_id" class="form-control w-50" required>
                <option value="">Sélectionner un rôle</option>
                <?php while ($role = mysqli_fetch_assoc($roles)): ?>
                    <option value="<?php echo $role['id']; ?>"><?php echo htmlspecialchars($role['role']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Ajouter</button>
        <button type="reset" class="btn btn-secondary">Annuler</button>
        </form>
        </div>
    </div>
</div>



<?php

mysqli_close($conn);
?>
