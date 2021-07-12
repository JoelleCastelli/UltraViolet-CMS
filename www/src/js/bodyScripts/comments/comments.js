$(document).ready(function () {
  /* BUILD DATATABLES */
  let table = $("#datatable").DataTable({
    responsive: true,
    // All columns
    columns: [
      { data: "Auteur" },
      { data: "Créer le" },
      { data: "Liée à" },
      { data: "Contenu" },
      { data: "Visibilité" },
      { data: "Actions" },
    ],

    // Column Actions
    columnDefs: [
      {
        targets: 5,
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

  /* Delete Comment*/
  table.on("click", ".delete", function (event) {
    event.preventDefault();
    if (confirm("Êtes-vous sûr de vouloir supprimer ce commentaire ?")) {
      let commentId = this.id.substring(this.id.lastIndexOf("-") + 1);
      let row = table.row($(this).parents("tr"));
      $.ajax({
        type: "POST",
        url: callRoute("comments_delete"),
        data: { id: commentId },
        success: function () {
          row.remove().draw();
        },
        error: function () {
          $(".header").after(
            "Erreur dans la suppression du commentaire ID: " + commentId
          );
        },
      });
    }
  });
});