<?php
session_start();
require "../config/config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: ../index.php");
    exit();
}
require "header.php";


if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: login.php");
    exit();
}

?>

<div class="container mt-4">
    <h1 class="text-center">Bienvenue, <?php echo htmlspecialchars($_SESSION["compte_utilisateur"]); ?>!</h1>
    <p class="text-center">
        <?php if (isset($_SESSION["role_id"])): ?>
            Vous êtes connecté en tant que <?php echo htmlspecialchars($_SESSION["role_id"]); ?>.
        <?php else: ?>
            Votre rôle n'est pas défini.
        <?php endif; ?>
    </p>
    <p class="text-center">
        <?php if (isset($_SESSION["fonction"])): ?>
            Votre fonction est : <?php echo htmlspecialchars($_SESSION["fonction"]); ?>.
        <?php else: ?>
            Votre fonction n'est pas définie.
        <?php endif; ?>
    </p>


<?php if (!empty($response)): ?>
        <div class="mt-4">
            <h4>Réponse de ChatGPT:</h4>
            <p><?php echo htmlspecialchars($response); ?></p>
        </div>
    <?php endif; ?>
</div>


<?php 
require "footer.php";
?>