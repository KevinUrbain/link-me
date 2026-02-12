function addLinkCard() {
  const addButton = document.querySelector("#addLinkModal .btn-primary");

  // On s'assure de ne pas empiler les écouteurs d'événements si la fonction est relancée
  // (Bonne pratique : cloner le bouton pour nettoyer les anciens events ou utiliser removeEventListener avant)
  // Pour cet exemple simple, on suppose que addLinkCard n'est lancé qu'une fois au chargement.

  addButton.addEventListener("click", function () {
    const titleInput = document.getElementById("linkTitle");
    const urlInput = document.getElementById("linkUrl");

    const title = titleInput.value.trim();
    const url = urlInput.value.trim();

    if (!title || !url) {
      alert("Veuillez remplir tous les champs");
      return;
    }

    // Crée la card
    const card = document.createElement("div");
    card.className = "link-card mb-3 p-3 border rounded";

    const index = Date.now();

    // --- CHANGEMENT ICI : Utilisation de la notation tableau [] ---
    // PHP transformera links[][title] en : links[0][title], links[1][title], etc.
    card.innerHTML = `
            <div class="d-flex align-items-center gap-3">
                <div class="flex-grow-1">
                    <input type="text" class="form-control mb-2" value="${title}" placeholder="Titre du lien" name="links[${index}][title]">
                    <input type="url" class="form-control" value="${url}" placeholder="https://..." name="links[${index}][url]">
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger delete-link-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                    </svg>
                </button>
            </div>
        `;

    // --- AJOUT : Gestion de la suppression ---
    // On attache l'événement directement à l'élément créé
    const deleteBtn = card.querySelector(".delete-link-btn");
    deleteBtn.addEventListener("click", function () {
      card.remove();
      // En supprimant l'élément du DOM, PHP ne le recevra pas.
      // Les index restants seront renumérotés automatiquement par PHP (0, 1, 2...).
    });

    // Ajoute la card à la liste
    document.getElementById("linksList").appendChild(card);

    // Réinitialise les champs et ferme la modale
    titleInput.value = "";
    urlInput.value = "";

    const modalEl = document.getElementById("addLinkModal");
    const modal = bootstrap.Modal.getInstance(modalEl);
    modal.hide();
  });
}

function enableCardDeletion() {
  // Récupère le conteneur parent de toutes les cards
  const container = document.getElementById("linksList");

  // Utilise l'event delegation
  container.addEventListener("click", function (e) {
    // Vérifie si l'utilisateur a cliqué sur un bouton avec la classe btn-outline-danger
    const btn = e.target.closest(".btn-outline-danger");
    if (!btn) return;

    // Supprime la card parente
    const card = btn.closest(".link-card");
    if (card) {
      card.remove();
    }
  });
}
