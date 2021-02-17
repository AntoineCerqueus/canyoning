// Attends le chargement du DOM
window.onload = () => {
    // Gestion des bouton/liens "supprimer"
    let links = document.querySelectorAll("[data-delete]"); // Trouve tout les éléments du DOM qui ont l'attribut 'data-delete'
    // console.log(links);

    for (link of links){
        // Ecoute le click sur le lien "supprimer"
        link.addEventListener("click", function(e){
            // empêche la navigation du lien
            e.preventDefault();

            // Confirmation de la suppression
            if (confirm('Etes-vous réellement suûr de vouloir supprimer cette image? ')){

            }
        })
    }


}