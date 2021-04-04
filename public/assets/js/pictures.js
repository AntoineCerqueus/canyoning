// Attends le chargement du DOM
window.onload = () => {
    // Gestion des liens "supprimer"
    // Récupèration de tout les éléments du DOM qui ont l'attribut 'data-delete'
    let links = document.querySelectorAll("[data-delete]");
    // console.log(links);

    // boucle for of = boucle for each de php
    for (link of links) {
        // Ecoute le click sur le lien "supprimer"
        link.addEventListener("click", function (e) {
            // empêche la redirection du lien
            e.preventDefault();
            // Message de confirmation de la suppression
            if (confirm("Es-tu réellement sûr de vouloir supprimer cette image? ")) {
                // Envoi via une requête Ajax vers le href du lien avec la méthode DELETE
                // fetch() envoie cette requête sous forme de promesse puis .then()
                // this fait référence au lien cliqué
                fetch(this.getAttribute("href"), { // Attrape l'attribut 'href' du lien
                    method: "DELETE",
                    // Informations envoyées en en-tête de la requête http
                    headers: {
                        // Evite d'initialiser un objet XMLHttpRequest grâce au fetch
                        "X-Requested-With": "XMLHttpRequest",
                        // Type MIME (Envoi de json car nous décodons du json dans le controller)
                        "Content-Type": "application/json"
                    },
                    // Envoi des données dans le body de requête http
                    // dataset regarde tout les attributs commençant par data + .token car c'est l'attribut data-token que nous voulons
                    body: JSON.stringify({
                        "_token": this.dataset.token
                    })
                    // => On envoie à l'url le href du lien et le token préparés dans la fonction deletePicture du controller
                }).then( // Une fois la promesse tenue, récupération de la réponse en json
                    // response est aussi une promesse donc une fois la réponse obtenue -> traitement des données
                    response => response.json()
                ).then(data => { // stocke les données dans la variable data
                    // si le contenu de la réponse de la fonction deletePicture est le succès, on enlève la div contenant le lien
                    if (data.succes)
                        this.parentElement.remove()
                    else
                        alert(data.error)
                }).catch(e => alert(e)) // Si jamais la promesse n'est pas tenue (serveur down, 404...)
            } // TO-DO fonction remove() ou rechargement de page dans le then
        })
    }
}