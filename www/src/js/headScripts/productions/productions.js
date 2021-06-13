$(document).ready( function () {

    /* BUILD DATATABLES */
    let table = $('#datatable').DataTable( {
        "order": [],
        "autoWidth": false,
        columns: [
            { data: 'Titre' },
            { data: 'Titre original' },
            { data: 'Durée' },
            { data: 'Date de sortie' },
            { data: 'Date d\'ajout' },
            { data: 'Actions' }
        ],

        columnDefs: [
            {
                targets: 5,
                data: "name",
                searchable: false,
                orderable: false
            },
            { width: "19%", targets: 0 },
            { width: "19%", targets: 1 },
            { width: "19%", targets: 2 },
            { width: "19%", targets: 3 },
            { width: "19%", targets: 4 },
            { width: "5%", targets: 5 },
        ],


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

    /* FILTERS */
    // On start, display movies
    getProductionsByType('movie');

    // Display different types on filtering button click
    $(".filtering-btn").click(function() {
        $(".filtering-btn").removeClass('active');
        $(this).addClass('active');
        getProductionsByType(this.id)
    });

    function getProductionsByType(productionType) {
        $.ajax({
            type: 'POST',
            url: '/admin/productions/productions-data', //TODO changer l'URL en dur
            data: { productionType },
            dataType: 'json',
            success: function(response) {
                table.clear();
                table.rows.add(response.productions).draw();
            },
            error: function(){
                console.log("Erreur dans la récupération des productions de type " + productionType);
            }
        });
    }

    table.on('click', '.delete', function () {
        if (confirm('Êtes-vous sûr.e de vouloir supprimer cette production ? Tous les articles relatifs à cette production seront supprimés')) {
            let productionId = this.id.substring(this.id.lastIndexOf('-') + 1);
            let row = table.row($(this).parents('tr'));
            $.ajax({
                type: 'POST',
                url: '/admin/productions/supprimer',
                data: { productionId },
                success: function() {
                    row.remove().draw();
                },
                error: function() {
                    console.log("Erreur dans la suppression de la production ID " + productionId);
                }
            });
        }
    });

});