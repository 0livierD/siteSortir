// Créer un élément de paragraphe
var messageElement = document.createElement("p");

// Ajouter du texte à l'élément
messageElement.textContent = "Ceci est un message ajouté au DOM.";

// Ajouter l'élément au corps (body) du document
document.body.appendChild(messageElement);

alert("Ceci est un message d'alerte depuis le fichier JavaScript !");
