$(document).ready(function () {
  /* BUILD DATATABLES */
  let tableProd = $("#datatable-production").DataTable({
    order: [],
    autoWidth: false,
    responsive: true,
    scrollY: "250px",
    columns: [
      { data: "Miniature" },
      { data: "Titre" },
      { data: "Identifiant" },

      { data: "Titre original" },
      { data: "Saison" },
      { data: "Série" },
      { data: "Durée" },
      { data: "Date de sortie" },
      { data: "Date d'ajout" },
      { data: "Actions" },
    ],
    scrollCollapse: true,

    columnDefs: [
      {
        targets: 0,
        data: "name",
        searchable: false,
        orderable: false,
      },
      {
        targets: 1,
        className: "production-name-cta",
      },
      {
        targets: 2,
        className: "production-id-cta",
      },
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
        sSortDescending:
          ": activer pour trier la colonne par ordre décroissant",
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

  /* FILTERS */
  // On start, display movies
  tableProd.columns([0, 1, 2]).visible(true); //thumbnail, title, id
  tableProd.columns([3, 4, 5, 6, 7, 8, 9]).visible(false);

  // Display different types on filtering button click
  $(".filtering-btn").click(function () {
    $(".filtering-btn").removeClass("active");
    $(this).addClass("active");
    getProductionsByType(this.id);

    switch (this.id) {
      case "season":
        /*         tableProd.columns([2]).visible(false); //original title
        tableProd.columns([3]).visible(false); // season
        tableProd.columns([4]).visible(true); // series */

        tableProd.columns([1]).visible(true); //thumbnail
        tableProd.columns([2]).visible(true); //title
        tableProd.columns([1]).visible(true); //original title
        tableProd.columns([3]).visible(false); // season
        tableProd.columns([4]).visible(true); // series
        break;
      case "episode":
        tableProd.columns([3]).visible(true); // season
        tableProd.columns([4]).visible(true); // series
        break;
      default:
        tableProd.columns([0, 1, 2]).visible(true); //thumbnail, title, id
        tableProd.columns([3, 4, 5, 6, 7, 8, 9]).visible(false);

        break;
    }
  });
  function listenRowEventsProductions() {
    const prodCTAs = document.querySelectorAll(".production-name-cta");

    prodCTAs.forEach((cta) => {
      cta.addEventListener("click", (e) => {
        idElement = e.target.parentNode.querySelector("td.production-id-cta");
        const prod = idElement.innerHTML;

        inputProd.value = e.target.innerHTML + " (" + prod + ")";
        modalProd.classList.toggle("visible");
      });
    });
  }

  function getProductionsByType(productionType) {
    $.ajax({
      type: "POST",
      url: callRoute("productions_data"),
      data: { productionType },
      dataType: "json",
      success: function (response) {
        tableProd.clear();
        tableProd.rows.add(response).draw();
        listenRowEventsProductions();
      },
      error: function () {
        console.log(
          "Erreur dans la récupération des productions de type " +
            productionType
        );
      },
    });
  }

  // SEPARATE
  const inputProd = document.querySelector("#production");
  const modalProd = document.querySelector(".background-modal-production");
  const removeBGProduction = document.querySelector(".clickable-bg");

  inputProd.addEventListener("click", (e) => {
    getProductionsByType("movie");
    modalProd.classList.add("visible");
  });

  removeBGProduction.addEventListener("click", (e) => {
    modalProd.classList.remove("visible");
  });
});
