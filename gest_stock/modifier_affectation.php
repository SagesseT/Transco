<?php
session_start();
require "../config/config.php";


if (!isset($_GET["id"])) {
    echo "<div class='alert alert-danger'>ID de l'affectation manquant.</div>";
    exit();
}

$id_affectation = intval($_GET["id"]);

// Charger les lignes
$ligne_query = "SELECT id_ligne, nom_ligne FROM lignes";
$result_lignes = mysqli_query($conn, $ligne_query);

// Charger l'affectation
$aff_query = "SELECT * FROM affectations WHERE id = ?";
$stmt = $conn->prepare($aff_query);
$stmt->bind_param("i", $id_affectation);
$stmt->execute();
$result = $stmt->get_result();
$affectation = $result->fetch_assoc();

if (!$affectation) {
    echo "<div class='alert alert-danger'>Affectation non trouvée.</div>";
    exit();
}

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function cleanInput($data) {
        return htmlspecialchars(trim($data));
    }

    $date = cleanInput($_POST['ddate']);
    $ligne = intval($_POST['ligne']);
    $service = intval($_POST['service']);
    $fr = cleanInput($_POST['fr']);
    $serie = cleanInput($_POST['serie']);
    $num_tickets_donner = intval($_POST['num_tickets_donner']);
    $num_tickets_retour = intval($_POST['num_tickets_retour']);
    $tr = intval($_POST['tr']);
    $total_tickets = intval($_POST['total_tickets']);
    $tv = intval($_POST['tv']);

    $update_sql = "UPDATE affectations SET
        lignes_id_ligne=?, services_id_service=?, fr=?, series=?, 
        num_tickets_donner=?, num_tickets_retour=?, tr=?, 
        total_tickets=?, tv=?, ddate=?
        WHERE id=?";

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param(
        "iissiiiiisi",
        $ligne, $service, $fr, $serie,
        $num_tickets_donner, $num_tickets_retour, $tr,
        $total_tickets, $tv, $date, $id_affectation
    );

    if ($stmt->execute()) {
        header("Location: affectations_list.php?updated=1");
        exit(); // important après header
    } else {
        echo "<div class='alert alert-danger'> Erreur lors de la mise à jour : " . $stmt->error . "</div>";
    }
    

    $stmt->close();
    $conn->close();
}
require "header.php";
?>
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

<div class="container mt-5">
    <h3>Modifier le affectation</h3>
    <form method="POST">
        <div class="row">
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" name="ddate" 
                   value="<?php echo htmlspecialchars($affectation['ddate']); ?>" required>
        </div>

        <!-- Ligne -->
        <div class="mb-3">
            <label for="ligne" class="form-label">Ligne</label>
            <select class="form-select" id="ligne" name="ligne" required>
                <option value="">Sélectionner une ligne...</option>
                <?php while ($ligne = mysqli_fetch_assoc($result_lignes)): ?>
                    <option value="<?php echo $ligne['id_ligne']; ?>"
                        <?php echo ($ligne['id_ligne'] == $affectation['lignes_id_ligne']) ? 'selected' : ''; ?>>
                        <?php echo $ligne['nom_ligne']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Service - Sera mis à jour dynamiquement -->
        <div class="mb-3">
            <label for="service" class="form-label">Service</label>
            <select class="form-select" id="service" name="service" required>
                <option value="">Sélectionner un service...</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="fr" class="form-label">Feuille de Route</label>
            <input type="text" class="form-control" id="fr" name="fr" 
                   value="<?php echo htmlspecialchars($affectation['fr']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="serie" class="form-label">Série</label>
            <input type="text" class="form-control" id="serie" name="serie" 
                   value="<?php echo htmlspecialchars($affectation['series']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="num_tickets_donner" class="form-label">Numéro de Tickets Donnés</label>
            <input type="number" class="form-control" id="num_tickets_donner" name="num_tickets_donner" 
                   value="<?php echo $affectation['num_tickets_donner']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="num_tickets_retour" class="form-label">Numéro de Tickets Retournés</label>
            <input type="number" class="form-control" id="num_tickets_retour" name="num_tickets_retour" 
                   value="<?php echo $affectation['num_tickets_retour']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="tr" class="form-label">TR</label>
            <input type="number" class="form-control" id="tr" name="tr" 
                   value="<?php echo $affectation['tr']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="total_tickets" class="form-label">Total Tickets</label>
            <input type="number" class="form-control" id="total_tickets" name="total_tickets" 
                   value="<?php echo $affectation['total_tickets']; ?>" readonly>
        </div>

        <div class="mb-3">
            <label for="tv" class="form-label">TV</label>
            <input type="number" class="form-control" id="tv" name="tv" 
                   value="<?php echo $affectation['tv']; ?>" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        <a href="liste_affectations.php" class="btn btn-secondary">Retour</a>
    </form>
</div>

<!-- Et pour les autres champs -->
<!-- Pour le <select> ligne, ajoute selected si l'id_ligne est égal à $affectation['lignes_id_ligne'] -->

<?php require "footer.php"; ?>
