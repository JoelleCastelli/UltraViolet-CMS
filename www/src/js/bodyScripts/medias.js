$(document).ready( function () {

    getMediasByType('poster');

    /* BUILD DATATABLES */
    let table = $('#datatable').DataTable( {
        "order": [],
        "autoWidth": false,
        responsive: true,
        columns: [
            { data: 'Miniature' },
            { data: 'Nom' },
            { data: 'Actions' }
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

    $(".filtering-btn").click(function() {
        $(".filtering-btn").removeClass('active');
        $(this).addClass('active');
        getMediasByType(this.id);
    });

    function getMediasByType(mediaType) {
        $.ajax({
            type: 'POST',
            url: '/admin/medias/medias-data', //TODO changer l'URL en dur
            data: { mediaType },
            dataType: 'json',
            success: function(response) {
                table.clear();
                table.rows.add(response).draw();
            },
            error: function(){
                console.log("Erreur dans la récupération des médias de type " + mediaType);
            }
        });
    }


    /*table.on('click', '.delete', function () {
        if (confirm('Êtes-vous sûr.e de vouloir supprimer cette production ?')) {
            let productionId = this.id.substring(this.id.lastIndexOf('-') + 1);
            let row = table.row($(this).parents('tr'));
            $.ajax({
                type: 'POST',
                url: '/admin/productions/supprimer',
                data: { productionId },
                dataType: 'json',
                success: function(response) {
                    if (response['success'])
                        row.remove().draw();
                    else
                        alert(response['message']);
                },
                error: function() {
                    console.log("Erreur dans la suppression de la production ID " + productionId);
                }
            });
        }
    });*/

});