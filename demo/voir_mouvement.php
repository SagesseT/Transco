<?php
session_start();
require "../config/config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: ../index.php");
    exit();
}

require "header.php";

// Sécuriser et récupérer l'ID du mouvement
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Mouvement introuvable.";
    header("Location: mouvements.php");
    exit();
}

$id = intval($_GET['id']);

// Requête pour récupérer les détails du mouvement
$sql = "SELECT m.*, td.nom AS document_nom 
        FROM mouvements m 
        JOIN types_documents td ON m.type_document_id = td.id 
        WHERE m.id = $id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "Ce mouvement n'existe pas.";
    header("Location: mouvements.php");
    exit();
}

$mouvement = mysqli_fetch_assoc($result);
?>

<div class="container mt-4">
    <h2 class="mb-4">Détail du Mouvement</h2>
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            Mouvement N° <?php echo $mouvement['id']; ?>
        </div>
        <div class="card-body">
            <p><strong>Type de document :</strong> <?php echo htmlspecialchars($mouvement['document_nom']); ?></p>
            <p><strong>Type de mouvement :</strong> 
                <?php if ($mouvement['type_mouvement'] == 'entree') {
                    echo '<span class="badge bg-success">Entrée</span>';
                } else {
                    echo '<span class="badge bg-danger">Sortie</span>';
                } ?>
            </p>
            <p><strong>Quantité :</strong> <?php echo htmlspecialchars($mouvement['quantite']); ?></p>
            <p><strong>Responsable :</strong> <?php echo htmlspecialchars($mouvement['responsable']); ?></p>
            <p><strong>Destinataire / Provenance :</strong> <?php echo htmlspecialchars($mouvement['destinataire']); ?></p>
            <p><strong>Date :</strong> <?php echo date('d/m/Y H:i', strtotime($mouvement['date_mouvement'])); ?></p>
            <p><strong>Commentaire :</strong> <?php echo nl2br(htmlspecialchars($mouvement['commentaire'])); ?></p>
        </div>
        <div class="card-footer text-end">
            <a href="mouvements.php" class="btn btn-secondary">Retour</a>
        </div>
    </div>
</div>

<?php require "footer.php"; ?>
