/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

document.addEventListener('DOMContentLoaded', function() {
    var selectVille = document.getElementById('select-ville');
    var selectLieu = document.getElementById('select-lieu');

    selectVille.addEventListener('change', function() {
        var selectedVilleId = this.value;

        // Effectuez une requête AJAX pour récupérer les lieux
        fetch('/getLieuxByVille/' + selectedVilleId)
            .then(response => response.json())
            .then(data => {
                // Mettez à jour la liste des lieux si l'élément select-lieu existe
                if (selectLieu) {
                    selectLieu.innerHTML = '';
                    data.forEach(lieu => {
                        var option = document.createElement('option');
                        option.value = lieu.id;
                        option.text = lieu.nom;
                        selectLieu.appendChild(option);
                    });
                } else {
                    console.error('L\'élément avec l\'id "select-lieu" n\'a pas été trouvé dans le DOM.');
                }
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des lieux:', error);
            });
    });
});
