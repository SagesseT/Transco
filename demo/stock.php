<?php
session_start();
require "../config/config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: ../index.php");
    exit();
}
require "header.php";


// Récupérer la liste des documents en stock
$sql_stock = "SELECT s.id, td.nom as type_document, s.quantite, s.date_entree, s.reference, s.emplacement 
        FROM stock s 
        JOIN types_documents td ON s.type_document_id = td.id 
        ORDER BY STR_TO_DATE(s.date_entree, '%Y-%m-%d %H:%i:%s') DESC";
$result = mysqli_query($conn, $sql_stock);

// Récupérer les types de documents pour le formulaire
$sql_types = "SELECT * FROM types_documents";
$result_types = mysqli_query($conn, $sql_types);
?>


 <!-- <div class="container mt-4">
        <h2 class="mb-4">Gestion de Stock</h2>
            Formulaire d'ajout -->
        <!--<div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5>Ajouter au Stock</h5>
            </div>
            <div class="card-body">
                <form action="controle/ajouter_stock.php" method="POST">
                    <div class="row">
                        <div class="col-md-4">
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
                                <label for="quantite" class="form-label">Quantité</label>
                                <input type="number" class="form-control" id="quantite" name="quantite" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="reference" class="form-label">Référence</label>
                                <input type="text" class="form-control" id="reference" name="reference" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="destinataire" class="form-label">Provenance</label>
                                <input type="text" class="form-control" id="destinataire" name="destinataire" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="emplacement" class="form-label">Emplacement</label>
                                <input type="text" class="form-control" id="emplacement" name="emplacement" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter au Stock</button>
                </form>
            </div>
        </div>  -->       
        <!-- Liste du stock -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5>Inventaire du Stock</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Type de Document</th>
                                <th>Quantité</th>
                                <th>Référence</th>
                                <th>Resp ou emplencement</th>
                                <th>Date Entrée</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)): 
                        ?>
                                 <tr>
                                    <td><?php echo htmlspecialchars($row['type_document']); ?></td>
                                    <td><?php echo htmlspecialchars($row['quantite']); ?></td>
                                    <td><?php echo htmlspecialchars($row['reference']); ?></td>
                                    <td><?php echo htmlspecialchars($row['emplacement']) ?></td>
                                    <td style="font-size: 11px;"><?php echo date('d/m/Y H:i', strtotime($row['date_entree'])); ?></td>
                                    <td>
                                         <a href="delete/supprimer_stock.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr?')"><i class="bi bi-trash"></i></a>
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
    </div>

         </main>
    </div>
    
</div>
<?php require "footer.php"; ?>