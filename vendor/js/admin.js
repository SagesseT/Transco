
document.getElementById("matricule").addEventListener("input", function() {
    if (this.value < 100001) {
        this.value = 100001; // Empêche les valeurs inférieures à 100001
    }
});
