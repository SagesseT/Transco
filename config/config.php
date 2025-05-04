<?php
// Database configuration

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'Transcotb';

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$compte_utilisateur = isset($_SESSION['compte_utilisateur']) ? $_SESSION['compte_utilisateur'] : null;
$role_id = isset($_SESSION['role_id']) ? $_SESSION['role_id'] : null;


$sql = "SELECT u.matricule, u.nom, u.postnom, u.prenom, f.fonction, g.grade, r.role 
        FROM utilisateur u
        JOIN fonction f ON u.fonction_id = f.id
        JOIN grade g ON u.grade_id = g.id
        JOIN role r ON u.role_id = r.id
        WHERE u.compte_utilisateur = '$compte_utilisateur'";

$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $_SESSION["role_id"] = $user['role'];
    $_SESSION["fonction"] = $user['fonction'];
    $_SESSION["matricule"] = $user['matricule'];
}

?>

<?php
// Fetch the total number of users
$count_query = "SELECT COUNT(*) AS total_users FROM utilisateur";
$count_result = mysqli_query($conn, $count_query);
$total_users = 0;

if ($count_result && mysqli_num_rows($count_result) > 0) {
    $row = mysqli_fetch_assoc($count_result);
    $total_users = $row['total_users'];
}
?>