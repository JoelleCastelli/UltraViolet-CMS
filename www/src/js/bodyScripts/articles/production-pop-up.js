$(document).ready(function () {
  console.log("production modal");
  /* BUILD DATATABLES */
  let tableProd = $("#datatable-production").DataTable({
    order: [],
    autoWidth: false,
    responsive: true,
    columns: [
      { data: "Miniature" },
      { data: "Titre" },
      { data: "Titre original" },
      { data: "Saison" },
      { data: "Série" },
      { data: "Durée" },
      { data: "Date de sortie" },
      { data: "Date d'ajout" },
      { data: "Actions" },
    ],
    scrollY: "400px",
    scrollCollapse: true,

    columnDefs: [
      { className: "production-name-cta", targets: [1] },
      {
        targets: 8, // Actions column
        data: "name",
        searchable: false,
        orderable: false,
      },
      { width: "12%", targets: 0 },
      { width: "12%", targets: 1 },
      { width: "12%", targets: 2 },
      { width: "12%", targets: 3 },
      { width: "12%", targets: 4 },
      { width: "12%", targets: 5 },
      { width: "12%", targets: 6 },
      { width: "12%", targets: 7 },
      { width: "5%", targets: 8 },
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

  /* FILTERS */
  // On start, display movies
  getProductionsByType("movie");

  tableProd.columns([3]).visible(false); // season
  tableProd.columns([4]).visible(false); // series
  // Display different types on filtering button click
  $(".filtering-btn").click(function () {
    $(".filtering-btn").removeClass("active");
    $(this).addClass("active");
    getProductionsByType(this.id);

    switch (this.id) {
      case "season":
        tableProd.columns([2]).visible(false); //original title
        tableProd.columns([3]).visible(false); // season
        tableProd.columns([4]).visible(true); // series
        break;
      case "episode":
        tableProd.columns([3]).visible(true); // season
        tableProd.columns([4]).visible(true); // series
        break;
      default:
        tableProd.columns([3]).visible(false); // season
        tableProd.columns([4]).visible(false); // series
        tableProd.columns([2]).visible(true); //original title
    }
  });
  function listenRowEventsProductions() {
    const mediaCTAs = document.querySelectorAll(".production-name-cta");
    mediaCTAs.forEach((cta) => {
      cta.addEventListener("click", (e) => {
        const media = e.target.innerHTML;
        console.log("nom du production get : " + media);

        inputProd.value = media;
        console.log(inputProd.value);
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
        console.log("Erreur dans la récupération des productions de type " + productionType);
      },
    });
  }

  // SEPARATE

  const inputProd = document.querySelector("#production");
  const modalProd = document.querySelector(".background-modal-production");
  // const removeBGProduction = document.querySelector(".clickable-bg");

 /*  inputProd.addEventListener("click", (e) => {
    modalProd.classList.add("visible");
  }); */

 /*  removeBGProduction.addEventListener("click", (e) => {
    modalProd.classList.remove("visible");
  }); */

});
