$(document).ready(function () {
  /* BUILD DATATABLES */
  let table = $("#datatable").DataTable({
    order: [],
    autoWidth: false,
    responsive: true,
    columns: [{ data: "Miniature" }, { data: "Nom" }, { data: "Date d'ajout" }],

    columnDefs: [{ className: "media-name-cta", targets: [1] }],

    language: {
      sEmptyTable: "Aucune donnée disponible dans le tableau",
      sInfo: "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
      sInfoEmpty: "Affichage de l'élément 0 à 0 sur 0 élément",
      sInfoFiltered: "(filtré à partir de _MAX_ éléments au total)",
      sInfoThousands: ",",
      sLengthMenu: "Afficher _MENU_ éléments",
      sLoadingRecords: "Chargement...",
      sProcessing: "Traitement...",
      sSearch: "",
      sZeroRecords: "Aucun élément correspondant trouvé",
      oPaginate: {
        sFirst: "Premier",
        sLast: "Dernier",
        sNext: "Suivant",
        sPrevious: "Précédent",
      },
      oAria: {
        sSortAscending: ": activer pour trier la colonne par ordre croissant",
        sSortDescending: ": activer pour trier la colonne par ordre décroissant",
      },
      select: {
        rows: {
          _: "%d lignes sélectionnées",
          0: "Aucune ligne sélectionnée",
          1: "1 ligne sélectionnée",
        },
      },
    },
  });

  function getMediasByType(mediaType) {
    $.ajax({
      type: "POST",
      url: callRoute("medias-data"),
      data: { mediaType },
      dataType: "json",
      success: function (response) {
        console.log(response);
        table.clear();
        table.rows.add(response).draw();
        listenRowEvents();
      },
      error: function () {
        console.log("Erreur dans la récupération des médias de type " + mediaType);
      },
    });
  }

  getMediasByType("other");
});

const input = document.querySelector("#media");
const modalMedia = document.querySelector(".background-modal");
const removeBG = document.querySelector(".clickable-bg");

input.addEventListener("click", (e) => {
  modalMedia.classList.toggle("visible");
});

removeBG.addEventListener("click", (e) => {
  modalMedia.classList.toggle("visible");
});

function listenRowEvents() {
  const mediaCTAs = document.querySelectorAll(".media-name-cta");
  mediaCTAs.forEach((cta) => {
    cta.addEventListener("click", (e) => {
      const media = e.target.innerHTML;
      console.log("nom du media get : " + media);

      input.value = media;
      console.log(input.value);
      modalMedia.classList.toggle("visible");
    });
  });
}
