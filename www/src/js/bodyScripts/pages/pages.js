$(document).ready(function () {
  /* BUILD DATATABLES */
  let table = $("#datatable").DataTable({
    order: [],
    autoWidth: false,
    responsive: true,

    // All columns
    columns: [
      { data: "Nom de la page" },
      { data: "URL de la page" },
      { data: "Ordre", width: "10%" },
      { data: "Visibilité", width: "10%" },
      { data: "Actions", width: "10%" },
    ],

    // Column Actions
    columnDefs: [
      {
        targets: 4,
        data: "Actions",
        searchable: false,
        orderable: false,
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
  // On start, display published pages
  getPagesByType("published");
  $("#published").addClass("active");

  // Display different types on filtering button click
  $(".filtering-btn").click(function () {
    $(".filtering-btn").removeClass("active");
    table.column([3]).visible(true); // visibility slider
    $(this).addClass("active");
    if (this.id !== "published") {
      table.column([3]).visible(false); // visibility slider
    }
    getPagesByType(this.id);
  });

  function getPagesByType(pageType) {
    $.ajax({
      type: "POST",
      url: callRoute("pages_data"),
      data: { pageType },
      dataType: "json",
      async: false,
      success: function (response) {
        table.clear();
        table.rows.add(response.pages).draw();
      },
      error: function (response) {
        $(".header").after(errorServerJS);
      },
    });
  }
  /* UPDATE STATE FOR DELETED PAGE */
  $("#datatable tbody").on(
    "click",
    ".state-draft, .state-hidden",
    function (event) {
      
      const pageId = this.id.substring(this.id.lastIndexOf("-") + 1);
      const row = table.row($(this).parents("tr"));
      const className = $(this).attr("class").split(' ')[0];
      const state = className.substring(className.lastIndexOf("-") + 1);
      
      $.ajax({
        type: "POST",
        url: callRoute("page_update_state"),
        data: {
          id: pageId,
          state: state,
        },
        dataType: "json",
        success: function (response) {
          if (response["success"]) {
            $("#response").html(successMessageForm(response["message"]));
            row.remove().draw();
          } else if (!response["success"]) {
            $("#response").html(errorMessageForm(response["message"]));
          }
        },
        error: function () {
          $("#response").html(
            "Erreur dans la suppression de la page ID " + pageId
          );
        },
      });
    }
  );

  /* UPDATE VISIBILITY PAGE */
  $("#datatable tbody").on("click", ".switch-visibily-page", function () {
    let id = $(this)[0].id.split("-");
    let pageId = id[id.length - 1];
    $.ajax({
      type: "POST",
      url: callRoute("page_update_visibility"),
      data: {
        id: pageId,
      },
      async: false,
      error: function (response) {
        $(".header").after(errorServerJS);
      },
    });
  });

  /* DELETE PAGE */
  table.on("click", ".delete", function (event) {
    event.preventDefault();
    if (confirm("Êtes-vous sûr.e de vouloir supprimer cette page ?")) {
      let pageId = this.id.substring(this.id.lastIndexOf("-") + 1);
      let row = table.row($(this).parents("tr"));
      $.ajax({
        type: "POST",
        url: callRoute("page_delete"),
        data: { id: pageId },
        success: function () {
          row.remove().draw();
        },
        error: function () {
          $(".header").after(
            "Erreur dans la suppression de la page ID " + pageId
          );
        },
      });
    }
  });

  // DISABLE DATE INPUT WHEN SELECTED OTHER THAN SCHEDULED CHECKBOX AT FIRST REFRESH
  if (!$(".stateScheduled").is(":checked")) {
    $(".publicationDateInput").prop("readonly", true);
  }

  // DISABLE DATE INPUT WHEN SELECTED OTHER THAN SCHEDULED CHECKBOX
  $(
    ".stateDraft, .statePublished, .stateScheduled, .statePublishedHidden"
  ).click(function () {
    if ($(this).hasClass("stateScheduled"))
      $(".publicationDateInput").prop("readonly", false);
    else $(".publicationDateInput").prop("readonly", true);
  });
});
