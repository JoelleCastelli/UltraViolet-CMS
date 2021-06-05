/* BUILD DATATABLES */
$(document).ready( function () {

    let table = $('#datatable').DataTable( {

        columns: [
            { data: 'Titre' },
            { data: 'Description' },
            { data: 'Note' },
            { data: 'Vues' },
            { data: 'Etat' },
            { data: 'Actions' }
        ],

        columnDefs: [{
            targets: 6,
            data: "name",
            searchable: false,
            orderable: false
        }],

        language: {
            "sEmptyTable":     "Aucune donnée disponible dans le tableau",
            "sInfo":           "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
            "sInfoEmpty":      "Affichage de l'élément 0 à 0 sur 0 élément",
            "sInfoFiltered":   "(filtré à partir de _MAX_ éléments au total)",
            "sInfoThousands":  ",",
            "sLengthMenu":     "Afficher _MENU_ éléments",
            "sLoadingRecords": "Chargement...",
            "sProcessing":     "Traitement...",
            "sSearch":         "",
            "sZeroRecords":    "Aucun élément correspondant trouvé",
            "oPaginate": {
                "sFirst":    "Premier",
                "sLast":     "Dernier",
                "sNext":     "Suivant",
                "sPrevious": "Précédent"
            },
            "oAria": {
                "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
            },
            "select": {
                "rows": {
                    "_": "%d lignes sélectionnées",
                    "0": "Aucune ligne sélectionnée",
                    "1": "1 ligne sélectionnée"
                }
            }
        },
    });

    // getArticleByState("testouille");

    function getArticleByState(state = "any") {
        $.ajax({
            type: 'POST',
            url: '/admin/articles/articles-data',
            data: { state },
            dataType: 'json',
            success: function(response) {
                console.log("Requete réussis");
                // table.clear();
                // table.rows.add(response.articles).draw();
            },
            error: function(){
                console.log("Erreur dans la récupération des articles :" + state);
            }
        });
    }

});