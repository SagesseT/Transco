<?php
session_start();
require "../config/config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: ../index.php");
    exit();
}
require "header.php";

$filtre_mois = isset($_GET['mois']) ? intval($_GET['mois']) : null;
$filtre_annee = isset($_GET['annee']) ? intval($_GET['annee']) : null;

$sql_mouvements = "SELECT m.id, td.nom as document, m.type_mouvement, m.quantite, 
                  m.date_mouvement, m.responsable, m.destinataire 
                  FROM mouvements m 
                  JOIN types_documents td ON m.type_document_id = td.id ";

if ($filtre_mois && $filtre_annee) {
    $sql_mouvements .= " WHERE MONTH(m.date_mouvement) = $filtre_mois AND YEAR(m.date_mouvement) = $filtre_annee ";
}

$sql_mouvements .= " ORDER BY m.date_mouvement DESC";
$result_mouvements = mysqli_query($conn, $sql_mouvements);


// Récupérer les types de documents pour le formulaire (nouvelle requête)
$sql_types = "SELECT * FROM types_documents";
$result_types = mysqli_query($conn, $sql_types);

?>


    <div class="container mt-4">
        <h2 class="mb-4">Gestion des Mouvements</h2>
        
        <!-- Formulaire d'ajout de mouvement -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5>Enregistrer un Mouvement</h5>
            </div>
            <div class="card-body">
                <form action="controle/ajouter_mouvement.php" method="POST">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="type_document" class="form-label">Type de Document</label>
                                <select class="form-select" id="type_document" name="type_document" required>
                                    <option value="">Sélectionner...</option>
                                    <?php while ($type = mysqli_fetch_assoc($result_types)): ?>
                                        <option value="<?php echo $type['id']; ?>"><?php echo $type['nom']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="type_mouvement" class="form-label">Type</label>
                                <select class="form-select" id="type_mouvement" name="type_mouvement" required>
                                    <option value="entree">Entrée</option>
                                    <option value="sortie">Sortie</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="quantite" class="form-label">Quantité</label>
                                <input type="number" class="form-control" id="quantite" min="1" name="quantite" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="reference" class="form-label">Reference</label>
                                <input type="text" class="form-control" id="reference" name="reference" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="destinataire" class="form-label">Destinataire</label>
                                <input type="text" class="form-control" id="destinataire" name="destinataire" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="responsable" class="form-label">Responsable</label>
                                <input type="text" class="form-control" id="responsable" name="responsable" value="<?php echo $_SESSION['compte_utilisateur']; ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="commentaire" class="form-label">Commentaire</label>
                        <textarea class="form-control" id="commentaire" name="commentaire" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>

                    <!-- Filtrer par mois et année -->
            <form class="row g-3 mb-3" method="GET" action="">
                <div class="col-md-3">
                    <label for="mois" class="form-label">Mois</label>
                    <select class="form-select" id="mois" name="mois" required>
                        <option value="">-- Choisir le mois --</option>
                        <?php
                        for ($m = 1; $m <= 12; $m++) {
                            $selected = (isset($_GET['mois']) && $_GET['mois'] == $m) ? 'selected' : '';
                            echo "<option value='$m' $selected>" . date('F', mktime(0, 0, 0, $m, 10)) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="annee" class="form-label">Année</label>
                    <input type="number" class="form-control" id="annee" name="annee" value="<?php echo isset($_GET['annee']) ? $_GET['annee'] : date('Y'); ?>" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                </div>
                <?php if (isset($_GET['mois']) && isset($_GET['annee'])): ?>
                    <div class="col-md-2 d-flex align-items-end">
                        <a href="controle/imprimer_mouvement.php?mois=<?php echo $_GET['mois']; ?>&annee=<?php echo $_GET['annee']; ?>" class="btn btn-success" target="_blank">
                            Imprimer
                        </a>
                    </div>
                <?php endif; ?>
            </form>
        
        <!-- Liste des mouvements -->
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5>Historique des Mouvements</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Document</th>
                        <th>Type</th>
                        <th>Quantité</th>
                        <th>Responsable</th>
                        <th>Destinataire/<br>Provenance</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (mysqli_num_rows($result_mouvements) > 0) {
                        while ($row = mysqli_fetch_assoc($result_mouvements)): 
                    ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['date_mouvement'])); ?></td>
                                <td><?php echo htmlspecialchars($row['document']); ?></td>
                                <td>
                                    <?php if ($row['type_mouvement'] == 'entree'): ?>
                                        <span class="badge bg-success">Entrée</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Sortie</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['quantite']); ?></td>
                                <td><?php echo htmlspecialchars($row['responsable']); ?></td>
                                <td><?php echo htmlspecialchars($row['destinataire']);  ?></td>
                                <td>
                                    <a href="voir_mouvement.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="modifier_mouvement.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>

                            </tr>
                        <?php endwhile; 
                    } else { ?>
                        <tr>
                            <td colspan="7" class="text-center">Aucun mouvement enregistré</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
    <!-- Your main content goes here -->
    </main>
    </div>
</div>
    </div>

<?php require "footer.php"; ?>