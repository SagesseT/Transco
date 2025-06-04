<?php
session_start();
require "../config/config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: ../index.php");
    exit();
}

// Fonction de sécurisation des entrées
function secure_input($data, $conn) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($conn, $data);
}

// Vérifier que l'ID du mouvement est fourni
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Aucun mouvement spécifié.";
    header("Location: mouvements.php");
    exit();
}

$mouvement_id = mysqli_real_escape_string($conn, $_GET['id']);

// Récupérer les infos du mouvement existant
$sql = "SELECT * FROM mouvements WHERE id = '$mouvement_id'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) != 1) {
    $_SESSION['error'] = "Mouvement introuvable.";
    header("Location: mouvements.php");
    exit();
}
$mouvement = mysqli_fetch_assoc($result);

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type_document = secure_input($_POST['type_document'], $conn);
    $type_mouvement = secure_input($_POST['type_mouvement'], $conn);
    $quantite = secure_input($_POST['quantite'], $conn);
    $reference = secure_input($_POST['reference'], $conn);
    $destinataire = secure_input($_POST['destinataire'], $conn);
    $responsable = $_SESSION['compte_utilisateur'];
    $commentaire = secure_input($_POST['commentaire'], $conn);

    // Étape 1 : Lire l'ancien mouvement
    $ancien_type = $mouvement['type_mouvement'];
    $ancienne_quantite = $mouvement['quantite'];
    $ancien_type_document = $mouvement['type_document_id'];

    // Étape 2 : Corriger le stock
    if ($ancien_type == 'entree') {
        // Retirer l'ancienne entrée du stock
        $sql_update_stock = "UPDATE stock SET quantite = quantite - '$ancienne_quantite' 
                             WHERE type_document_id = '$ancien_type_document' 
                             ORDER BY date_entree DESC LIMIT 1";
        mysqli_query($conn, $sql_update_stock);
    } elseif ($ancien_type == 'sortie') {
        // Ajouter l'ancienne sortie au stock (annuler la sortie)
        $sql_update_stock = "UPDATE stock SET quantite = quantite + '$ancienne_quantite' 
                             WHERE type_document_id = '$ancien_type_document' 
                             ORDER BY date_entree DESC LIMIT 1";
        mysqli_query($conn, $sql_update_stock);
    }

    // Étape 3 : Appliquer la nouvelle modification
    if ($type_mouvement == 'entree') {
        $sql_update_stock = "UPDATE stock SET quantite = quantite + '$quantite' 
                             WHERE type_document_id = '$type_document' 
                             ORDER BY date_entree DESC LIMIT 1";
        mysqli_query($conn, $sql_update_stock);
    } elseif ($type_mouvement == 'sortie') {
        // Vérification si stock suffisant
        $sql_stock = "SELECT SUM(quantite) AS total FROM stock WHERE type_document_id = '$type_document'";
        $result_stock = mysqli_query($conn, $sql_stock);
        $stock = mysqli_fetch_assoc($result_stock);
        if ($stock['total'] < $quantite) {
            $_SESSION['error'] = "Stock insuffisant pour cette sortie.";
            header("Location: mouvements.php");
            exit();
        }

        // Retirer la nouvelle sortie du stock
        $sql_update_stock = "UPDATE stock SET quantite = quantite - '$quantite' 
                             WHERE type_document_id = '$type_document' 
                             ORDER BY date_entree DESC LIMIT 1";
        mysqli_query($conn, $sql_update_stock);
    }

    // Étape 4 : Mettre à jour le mouvement
    $sql_update = "UPDATE mouvements SET 
                    type_document_id = '$type_document',
                    type_mouvement = '$type_mouvement',
                    quantite = '$quantite',
                    responsable = '$responsable',
                    destinataire = '$destinataire',
                    commentaire = '$commentaire',
                    date_mouvement = NOW()
                   WHERE id = '$mouvement_id'";

    if (mysqli_query($conn, $sql_update)) {
        $_SESSION['success'] = "Mouvement modifié et stock mis à jour.";
    } else {
        $_SESSION['error'] = "Erreur lors de la mise à jour du mouvement.";
    }

    header("Location: mouvements.php");
    exit();
}

?>

<!-- HTML du formulaire -->
<?php require "header.php"; ?>

<div class="container mt-4">
    <h3>Modifier le Mouvement</h3>
    <form method="POST">
        <div class="row">
            <!-- Type document -->
            <div class="col-md-4">
                <label class="form-label">Type de Document</label>
                <select name="type_document" class="form-select" required>
                    <?php
                    $docs = mysqli_query($conn, "SELECT * FROM types_documents");
                    while ($doc = mysqli_fetch_assoc($docs)) {
                        $selected = ($doc['id'] == $mouvement['type_document_id']) ? 'selected' : '';
                        echo "<option value='".$doc['id']."' $selected>".$doc['nom']."</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Type mouvement -->
            <div class="col-md-3">
                <label class="form-label">Type de Mouvement</label>
                <select name="type_mouvement" class="form-select" required>
                <option value="entree" <?php if ($mouvement['type_mouvement'] == 'entree') echo 'selected'; ?>>Entrée</option>
                <option value="sortie" <?php if ($mouvement['type_mouvement'] == 'sortie') echo 'selected'; ?>>Sortie</option>
                </select>
            </div>

            <!-- Quantité -->
            <div class="col-md-2">
                <label class="form-label">Quantité</label>
                <input type="number" name="quantite" class="form-control" min="1" value="<?= $mouvement['quantite'] ?>" required>
            </div>

            <!-- Référence -->
            <div class="col-md-3">
                <label class="form-label">Référence</label>
                <input type="text" name="reference" class="form-control" value="<?= htmlspecialchars($mouvement['commentaire']) ?>" required>
            </div>

            <!-- Destinataire -->
            <div class="col-md-6 mt-3">
                <label class="form-label">Destinataire</label>
                <input type="text" name="destinataire" class="form-control" value="<?= htmlspecialchars($mouvement['destinataire']) ?>" required>
            </div>

            <!-- Commentaire -->
            <div class="col-md-6 mt-3">
                <label class="form-label">Commentaire</label>
                <input type="text" name="commentaire" class="form-control" value="<?= htmlspecialchars($mouvement['commentaire']) ?>">
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Enregistrer</button>
        <a href="mouvements.php" class="btn btn-secondary mt-3">Annuler</a>
    </form>
</div>

<?php require "footer.php"; ?>
