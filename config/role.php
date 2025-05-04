<?php


// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Rediriger vers la page de connexion si non connecté
    exit;
}

// Récupérer l'ID de l'utilisateur depuis la session
$user_id = $_SESSION['user_id'];

// Vérifier le rôle de l'utilisateur
$query_role = "SELECT role_id FROM utilisateur WHERE matricule = ?";
$stmt = $conn->prepare($query_role);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($role_id);
$stmt->fetch();
$stmt->close();

// Vérifier si l'utilisateur a le rôle "Admin" (assurez-vous que le rôle "Admin" a l'id approprié dans la table roles)
$admin_role_id = "Admin"; // Remplacez 1 par l'ID réel du rôle Admin dans votre base de données
if ($role_id != $admin_role_id) {
    header('Location: 404.php'); // Rediriger vers une autre page si ce n'est pas un admin
    exit;
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

// Ajoutez le reste de votre logique de traitement des utilisateurs ici...

?>
