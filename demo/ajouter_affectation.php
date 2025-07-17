<?php
session_start();
require "../config/config.php";
require "header.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["compte_utilisateur"])) {
    header("Location: ../index.php");
    exit();
}

// Récupérer les lignes depuis la base de données
$ligne_query = "SELECT id_ligne, nom_ligne FROM lignes";
$result_lignes = mysqli_query($conn, $ligne_query);

if (!$result_lignes) {
    die("<div class='alert alert-danger'>Erreur de récupération des lignes : " . mysqli_error($conn) . "</div>");
}
?>



<style>
    .container {
        max-width: 600px;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const numTicketsDonner = document.getElementById('num_tickets_donner');
    const numTicketsRetour = document.getElementById('num_tickets_retour');
    const tr = document.getElementById('tr');
    const totalTickets = document.getElementById('total_tickets');
    const tv = document.getElementById('tv');

    function calculerTotalTickets() {
        const donner = parseInt(numTicketsDonner.value) || 0;
        const retour = parseInt(numTicketsRetour.value) || 0;
        const total = retour - donner + 1;
        totalTickets.value = total;
        calculerTV();
    }

    function calculerTV() {
        const total = parseInt(totalTickets.value) || 0;
        const trValue = parseInt(tr.value) || 0;
        const tvValue = total - trValue;
        tv.value = tvValue;
    }

    numTicketsDonner.addEventListener('input', calculerTotalTickets);
    numTicketsRetour.addEventListener('input', calculerTotalTickets);
    tr.addEventListener('input', calculerTV);
});


document.addEventListener('DOMContentLoaded', function () {
    const ligneSelect = document.getElementById('ligne');
    const serviceSelect = document.getElementById('service');

    // Fonction pour charger les services associés à la ligne
    function loadServices(ligneId) {
        serviceSelect.innerHTML = '<option value="">Sélectionner un service...</option>';  // Réinitialiser la liste des services

        if (!ligneId) return;  // Si aucune ligne n'est sélectionnée, ne rien faire

        // Requête AJAX pour récupérer les services
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_services.php?ligne_id=' + ligneId, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                const services = JSON.parse(xhr.responseText);
                
                if (services.length > 0) {
                    services.forEach(service => {
                        const option = document.createElement('option');
                        option.value = service.id_service;
                        option.textContent = service.nom_service;
                        serviceSelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.textContent = 'Aucun service disponible';
                    serviceSelect.appendChild(option);
                }
            } else {
                alert('Erreur lors du chargement des services.');
            }
        };
        xhr.send();
    }

    // Événement pour charger les services lorsqu'une ligne est sélectionnée
    ligneSelect.addEventListener('change', function() {
        loadServices(this.value);
    });
});
</script>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="alert alert-success"> Affectation enregistrée avec succès.</div>
<?php endif; ?>

<link href="../css/ajaffectation.css" rel="stylesheet">
<div class="container mt-5">
    <div class="form-title">Ajouter une Nouvelle Affectation</div>
    <form method="post" action="controle/ajouter_affectation.php">
        <div class="row">
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" name="ddate" value="<?php echo isset($_GET['date_filter']) ? htmlspecialchars($_GET['date_filter']) : date('Y-m-d'); ?>" required>
            </div>
            <div class="mb-3">
            <label for="ligne" class="form-label">Ligne</label>
            <select class="form-select" id="ligne" name="ligne" required>
                <option value="">Sélectionner une ligne...</option>
                <?php while ($ligne = mysqli_fetch_assoc($result_lignes)): ?>
                    <option value="<?php echo $ligne['id_ligne']; ?>"><?php echo $ligne['nom_ligne']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        </div>
        <div class="row">
            <div class="mb-3">
                <label for="service" class="form-label">Service</label>
                <select class="form-select" id="service" name="service" required>
                    <option value="">Sélectionner un service...</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="fr" class="form-label">Feuille de Route</label>
                <input type="text" class="form-control" id="fr" name="fr" required>
            </div>
        </div>
        <div class="row">
            <div class="mb-3">
                <label for="serie" class="form-label">Série</label>
                <input type="text" class="form-control" id="serie" name="serie" required>
            </div>
            <div class="mb-3">
                <label for="num_tickets_donner" class="form-label">N° Tickets Donnés</label>
                <input type="number" class="form-control" id="num_tickets_donner" name="num_tickets_donner" required>
            </div>
        </div>
        <div class="row">
            <div class="mb-3">
                <label for="num_tickets_retour" class="form-label">N° Tickets Retournés</label>
                <input type="number" class="form-control" id="num_tickets_retour" name="num_tickets_retour" required>
            </div>
            <div class="mb-3">
                <label for="tr" class="form-label">Tickets Retournés</label>
                <input type="number" class="form-control" id="tr" name="tr" required>
            </div>
        </div>
        <div class="row">
            <div class="mb-3">
                <label for="total_tickets" class="form-label">Total Tickets</label>
                <input type="number" class="form-control" id="total_tickets" name="total_tickets" readonly>
            </div>
            <div class="mb-3">
                <label for="tv" class="form-label">Tickets Vendus</label>
                <input type="number" class="form-control" id="tv" name="tv" readonly>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
        <button type="reset" class="btn btn-secondary">Annuler</button>
    </form>
</div>
<!-- ...existing code... -->
<?php require "footer.php"; ?>
