<?php
session_start();
require "../config/config.php";


// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location:../login.php");
    exit();
}

// Vérifier si le matricule et le nom de l'utilisateur sont passés en paramètre dans l'URL
if (isset($_GET['matricule']) && isset($_GET['nom'])) {
    $matricule = mysqli_real_escape_string($conn, $_GET['matricule']);
    $nom = mysqli_real_escape_string($conn, $_GET['nom']);
    
    // Récupérer les données de l'utilisateur à modifier
    $query = "SELECT * FROM utilisateur WHERE matricule = '$matricule'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $utilisateur = mysqli_fetch_assoc($result);
    } else {
        echo "<div class='alert alert-danger'>Utilisateur non trouvé.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>Matricule ou nom manquant pour modification.</div>";
    exit();
}


// Récupérer les options pour fonction, grade et rôle
$fonctions = mysqli_query($conn, "SELECT * FROM fonction");
$grades = mysqli_query($conn, "SELECT * FROM grade");
$roles = mysqli_query($conn, "SELECT * FROM role");

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricule = mysqli_real_escape_string($conn, $_POST['matricule']);
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $postnom = mysqli_real_escape_string($conn, $_POST['postnom']);
    $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
    $compte_utilisateur = mysqli_real_escape_string($conn, $_POST['compte_utilisateur']);
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT); //  password hashé
    $fonction_id = mysqli_real_escape_string($conn, $_POST['fonction_id']);
    $grade_id = mysqli_real_escape_string($conn, $_POST['grade_id']);
    $role_id = mysqli_real_escape_string($conn, $_POST['role_id']);

    // Mettre à jour les informations de l'utilisateur dans la base de données
    $update_query = "UPDATE utilisateur 
                     SET matricule = '$matricule', nom = '$nom', postnom = '$postnom', prenom = '$prenom', compte_utilisateur = '$compte_utilisateur', 
                         mot_de_passe = '$mot_de_passe', fonction_id = '$fonction_id', grade_id = '$grade_id', role_id = '$role_id' 
                     WHERE matricule = '$matricule' ";

    if (mysqli_query($conn, $update_query)) {
        echo "<div class='alert alert-success'>Utilisateur mis à jour avec succès.</div>";
        // Redirection vers la page d'accueil après succès
        header("Location: utilisateur.php");
        exit(); // N'oubliez pas d'appeler exit après la redirection pour stopper l'exécution du script
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de la mise à jour : " . mysqli_error($conn) . "</div>";
    }
}
?>
<!DOCTYPE html> 
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un utilisateur</title>
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
    
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center">
            <h3>Modifier un utilisateur</h3>
        </div>
        <div class="card-body">
            <form method="post" action="">
                <div class="mb-3">
                    <label for="matricule" class="form-label">Matricule</label>
                    <input type="number" name="matricule" id="matricule" class="form-control w-50" min="100001" size="20" required 
                        value="<?php echo htmlspecialchars($utilisateur['matricule']); ?>">
                </div>
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" name="nom" id="nom" class="form-control w-50" required value="<?php echo htmlspecialchars($utilisateur['nom']); ?>">
                </div>
                <div class="mb-3">
                    <label for="postnom" class="form-label">Postnom</label>
                    <input type="text" name="postnom" id="postnom" class="form-control w-50" required value="<?php echo htmlspecialchars($utilisateur['postnom']); ?>">
                </div>
                <div class="mb-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" name="prenom" id="prenom" class="form-control w-50" required value="<?php echo htmlspecialchars($utilisateur['prenom']); ?>">
                </div>
                <div class="mb-3">
                    <label for="compte_utilisateur" class="form-label">Compte Utilisateur</label>
                    <input type="text" name="compte_utilisateur" id="compte_utilisateur" class="form-control w-50" required value="<?php echo htmlspecialchars($utilisateur['compte_utilisateur']); ?>">
                </div>
                <div class="mb-3">
                    <label for="mot_de_passe" class="form-label">Mot de Passe</label>
                    <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control w-50" required value="<?php echo htmlspecialchars($utilisateur['mot_de_passe']); ?>">
                </div>
                <div class="mb-3">
                    <label for="fonction_id" class="form-label">Fonction</label>
                    <select name="fonction_id" id="fonction_id" class="form-control w-50" required>
                        <option value="">Sélectionner une fonction</option>
                        <?php while ($fonction = mysqli_fetch_assoc($fonctions)): ?>
                            <option value="<?php echo $fonction['id']; ?>" <?php echo $fonction['id'] == $utilisateur['fonction_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($fonction['fonction']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="grade_id" class="form-label">Grade</label>
                    <select name="grade_id" id="grade_id" class="form-control w-50" required>
                        <option value="">Sélectionner un grade</option>
                        <?php while ($grade = mysqli_fetch_assoc($grades)): ?>
                            <option value="<?php echo $grade['id']; ?>" <?php echo $grade['id'] == $utilisateur['grade_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($grade['grade']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="role_id" class="form-label">Role</label>
                    <select name="role_id" id="role_id" class="form-control w-50" required>
                        <option value="">Sélectionner un rôle</option>
                        <?php while ($role = mysqli_fetch_assoc($roles)): ?>
                            <option value="<?php echo $role['id']; ?>" <?php echo $role['id'] == $utilisateur['role_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($role['role']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Modifier</button>
                <a href="utilisateur.php" class="btn btn-secondary">Annuler</a> <!-- Bouton Annuler -->
            </form>
        </div>
    </div>
</div>

<?php

mysqli_close($conn);
?>
