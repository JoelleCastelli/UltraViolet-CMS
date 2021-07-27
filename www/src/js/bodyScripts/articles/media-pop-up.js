a = 0;

$(document).ready(function () {
  /* BUILD DATATABLES */
  let table = $("#datatable").DataTable({
    order: [],
    autoWidth: false,
    responsive: true,
    columns: 
      [
        { data: "Miniature" }, 
        { data: "Nom" }, 
        { data: "Identifiant" }
      ],

    scrollY: "200px",
    scrollCollapse: true,
    columnDefs: 
      [
        {
          targets: 0,
          data: "name",
          searchable: false,
          orderable: false,
        },
        { 
          className: "media-name-cta", 
          targets: [1] 
        },
        { 
          className: "media-id-cta", 
          targets: [2] 
        }
      ],

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
        table.clear();
        table.rows.add(response).draw();
        listenRowEvents();
      },
      error: function () {
        console.log("Erreur dans la récupération des médias de type " + mediaType);
      },
    });
  }

  $(".filtering-btn").click(function () {
    $(".filtering-btn").removeClass("active");
    $(this).addClass("active");
    getMediasByType(this.id);
  });

  getMediasByType("poster");
});

// get bg to quit modals
const removeBGs = document.querySelectorAll(".clickable-bg");

// init media
const input = document.querySelector("#media");
const modalMedia = document.querySelector(".background-modal");

// init production
const inputProd = document.querySelector("#production");
const modalProd = document.querySelector(".background-modal-production");

// Open media modal
input.addEventListener("click", (e) => {
  modalMedia.classList.add("visible");
});

// Open prod modal
inputProd.addEventListener("click", (e) => {
  modalProd.classList.add("visible");
});

// Close media and prod modal
removeBGs.forEach(item => {
  item.addEventListener('click', event => {
    modalProd.classList.remove("visible");
    modalMedia.classList.remove("visible");
  });
});

function listenRowEvents() {
  const mediaCTAs = document.querySelectorAll(".media-name-cta");
  mediaCTAs.forEach((cta) => {
    cta.addEventListener("click", (e) => {
      idElement = e.target.parentNode.querySelector("td.media-id-cta");
      const media = idElement.innerHTML;
      input.value = e.target.innerHTML + " (" + media + ")";
      modalMedia.classList.toggle("visible");
    });
  });
}
