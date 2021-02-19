// Attends le chargement du DOM
window.onload = () => {
    // Gestion des bouton/liens "supprimer"
    let links = document.querySelectorAll("[data-delete]"); // Trouve tout les éléments du DOM qui ont l'attribut 'data-delete'
    // console.log(links);

    for (link of links) {
        // Ecoute le click sur le lien "supprimer"
        link.addEventListener("click", function (e) {
            // empêche la navigation du lien
            e.preventDefault();
            // Message de confirmation de la suppression
            if (confirm('Etes-vous réellement sûr de vouloir supprimer cette image? ')) {
                // Envoi via une requête Ajax vers le href du lien avec la méthode DELETE
                // fetch() envoie cette requête sous forme de promesse puis .then()
                // this fait référence au lien cliqué
                fetch(this.getAttribute("href"), { // Attrape l'attribut 'href' du lien
                    method: "DELETE",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest", // Evite d'initialiser un objet XMLHttpRequest grâce au fetch
                        "Content-Type": "application/json" // Type MIME (Envoi de json car nous décodons du json dnas le controller)
                    },
                    // dataset regarde tout les attributs commençant par data et .token car c'est l'attribut avec token que nous voulons
                    body: JSON.stringify({
                        '_token': this.dataset.token
                    })
                    // => On envoie à l'url le href du lien et le token préparés dans la fonction deletePicture du controller
                }).then( // Une fois la promesse tenue, récupération de la réponse en json
                    // response est aussi une promesse donc une fois la réponse obtenue -> traitement des données
                    response => response.json()
                ).then(data => { // stocke les données dans la variable data
                    // si le contenu de la réponse de la fonction deletePicture est le succes, on enlève la div contenant le lien
                    if (data.succes)
                        this.parentElement.remove()
                    else
                        alert(data.error)
                }).catch(e => alert(e)) // Si jamais la promesse n'est pas tenue (serveur down, 404...)
            }
        })
    }


}