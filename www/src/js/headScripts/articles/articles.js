/* BUILD DATATABLES */
$(document).ready(function () {
  let table = $("#datatable").DataTable({
    responsive: true,
    columns: [
      { data: "Titre" },
      { data: "Auteur" },
      { data: "Vues" },
      { data: "Commentaire" },
      { data: "Date creation" },
      { data: "Date publication" },
      { data: "Actions" },
    ],

    columnDefs: [
      {
        targets: 6,
        data: "name",
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

  getArticleByState("draft");

  //  Display different types on filtering button click
  $(".filtering-btn").click(function () {
    $(".filtering-btn").removeClass("active");
    table.column([4]).visible(true);
    $(this).addClass("active");
    if (this.id !== "published") {
      table.column([4]).visible(false);
    }
    getArticleByState(this.id);
  });

  function getArticleByState(state) {
    $.ajax({
      type: "POST",
      url: callRoute("article_data"),
      data: { state: state },
      dataType: "json",
      success: function (response) {
        console.log("Requete réussis");
        console.log(response.articles);
        table.clear();
        table.rows.add(response.articles).draw();
      },
      error: function (response) {
        console.log("Erreur dans la récupération des articles");
        console.log(response);
      },
    });
  }

  /* DELETE Article */
  table.on("click", ".delete", function (event) {
    event.preventDefault();
    if (confirm("Êtes-vous sûr.e de vouloir supprimer cet article ?")) {
      const id = this.id.substring(this.id.lastIndexOf("-") + 1);
      const row = table.row($(this).parents("tr"));
      $.ajax({
        type: "POST",
        url: callRoute("article_delete"),
        data: { id: id },
        success: function () {
          row.remove().draw();
        },
        error: function () {
          $(".header").after(
            "Erreur : impossible de supprimer l'article avec l'ID suivant :  " + id
          );
        },
      });
    }
  });
});

document.addEventListener("readystatechange", (event) => {
  if (document.readyState === "complete") {
    document.querySelector("#state").addEventListener("click", (event) => {
      console.log("AKE COUCOU");
      console.log(event);
      console.log(event.target);
      console.log(event.target.value);
    });

    console.log("READY POUR ARTICLES");
  }
});
