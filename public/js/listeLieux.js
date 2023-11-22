document.addEventListener('DOMContentLoaded', function() {
    var selectVille = document.getElementById('select-ville');
    var selectLieu = document.getElementById('sortie_lieu');

    selectVille.addEventListener('change', function() {
        var selectedVilleId = this.value;

        // Effectuez une requête AJAX pour récupérer les lieux
        fetch('/lieux-par-villes/' + selectedVilleId)
            .then(response => response.json())
            .then(data => {
                // Mettez à jour la liste des lieux si l'élément select-lieu existe
                if (selectLieu) {
                    selectLieu.innerHTML = ''; // Efface les anciennes options
                    data.forEach(lieu => {
                        var option = document.createElement('option');
                        option.value = lieu.id;
                        option.text = lieu.nom;
                        selectLieu.appendChild(option);
                    });
                } else {
                    console.error('L\'élément avec l\'id "sortie_lieu" n\'a pas été trouvé dans le DOM.');
                }
            })
            .catch(error => {

                console.error('Erreur lors de la récupération des lieux:', error);
            });
    });
});
