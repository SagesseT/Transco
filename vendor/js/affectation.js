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
