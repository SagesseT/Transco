<?php
session_start();
require_once "config/config.php"; // Connexion à la base de données

$error = ""; // Message d'erreur

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var(trim($_POST["compte_utilisateur"]), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST["mot_de_passe"]);

    if (!empty($email) && !empty($password)) {
        // Vérifier si l'utilisateur existe
        $sql = "SELECT * FROM utilisateur WHERE compte_utilisateur = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                // Vérification du mot de passe
                if (password_verify($password, $row["mot_de_passe"])) { // Remplace par password_verify($password, $row["mot_de_passe"]) si haché
                    // Enregistrer les infos de l'utilisateur dans la session
                    $_SESSION["user_id"] = $row["matricule"];
                    $_SESSION["compte_utilisateur"] = $row["compte_utilisateur"];
                    $_SESSION["role_id"] = $row["role_id"];
                    $_SESSION["fonction"] = $row["fonction"];

                    // Enregistrer la connexion dans la table 'connexion'
                    $matricule = $row['matricule'];
                    $compte_utilisateur = $row['compte_utilisateur'];
                    $role_id = $row['role_id'];
                    
                    $insert_sql = "INSERT INTO connexion (matricule, compte_utilisateur, role_id, date_connexion) VALUES (?, ?, ?, NOW())";
                    if ($stmt_insert = $conn->prepare($insert_sql)) {
                        $stmt_insert->bind_param("sss", $matricule, $compte_utilisateur, $role_id);
                        $stmt_insert->execute();
                        $stmt_insert->close();
                    } else {
                        $error = "Erreur lors de l'enregistrement de la connexion : " . $conn->error;
                    }

                    // Rediriger vers la page appropriée en fonction du rôle
                    switch ($_SESSION["role_id"]) {
                        case 'Admin':
                            header("Location: accueil.php");
                            break;
                        case 'Autor':
                            header("Location: accueil.php");
                            break;
                        case 'SUP':
                            header("Location: superviseur/index.php");
                            break;
                        case 'GS':
                            header("Location: gest_stock/index.php");
                            break;
                        case 'CAV':
                            header("Location: controle/index.php");
                            break;
                        case 'Rapro':
                            header("Location: rapprochement/index.php");
                            break;
                        case 'CSSB':
                            header("");
                            break;
                        default:
                            // Si le rôle n'est pas trouvé, redirigez vers la page par défaut
                            header("Location: index.php");
                            break;
                    }
                    exit();
                } else {
                    $error = "Mot de passe incorrect.";
                }
            } else {
                $error = "Utilisateur non trouvé.";
            }

            $stmt->close();
        } else {
            $error = "Erreur lors de la préparation de la requête : " . $conn->error;
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="vendor/bootstrapc/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="vendor/aos/aos.css" rel="stylesheet">
    <link href="vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <title>Connexion</title>
</head>
<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Connexion</h3>
                                </div>
                                <?php if (!empty($error)) { ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo htmlspecialchars($error); ?>
                                    </div>
                                <?php } ?>
                                <div class="card-body">
                                    <form method="post" class="form-login">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="compte_utilisateur" id="inputEmail" type="text" placeholder="name@example.com" required />
                                            <label for="inputEmail">Email</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="mot_de_passe" id="inputPassword" type="password" placeholder="Password" required />
                                            <label for="inputPassword">Mot de passe</label>
                                        </div>
                                    <div class="mb-3">
                                        <input type="checkbox" id="showPassword" onclick="togglePassword()" />
                                        <label for="showPassword">Afficher le mot de passe</label>
                                    </div>

                                        <script>
                                        function togglePassword() {
                                            const pwd = document.getElementById('inputPassword');
                                            pwd.type = pwd.type === 'password' ? 'text' : 'password';
                                        }
                                        </script>
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <button class="btn btn-primary" type="submit">Connexion</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
