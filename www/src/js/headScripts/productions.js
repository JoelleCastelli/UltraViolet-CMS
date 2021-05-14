/* BUILD DATATABLES */
$(document).ready( function () {

    $('#datatable').DataTable( {

        columns: [
            { data: 'Titre' },
            { data: 'Titre original' },
            { data: 'Date de sortie' },
            { data: 'Durée' },
            { data: 'Résumé' },
            { data: 'Actions' }
        ],

        columnDefs: [{
            targets:5,
            data:"name",
            render: function(data,type,full,meta)
            {
                return "<button>Add</button>"
            },
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

    $("#datatable_filter label").before( "<i class='fas fa-search'>" );
    $(".dataTables_wrapper").children().slice(0,2).wrapAll( "<div id='tableHeader'></div>" );
    wrapDatatablesFooter();
} );

/* PRODUCTIONS DISPLAY */
$(document).ready(function(){

    function getAllProductions()
    {
        $.ajax({
            type: 'POST',
            url: '/admin/production/productions-data',
            dataType: 'json',
            success: function(response) {

                table.clear();
                table.rows.add(response.productions).draw();
            },
            error: function(){
                alert("error");
            }
        });
    }

    function getProductionsByType(productionType)
    {
        $.ajax({
            type: 'POST',
            url: '/admin/production/productions-data',
            data: {productionType},
            dataType: 'json',
            success: function(response) {
                table.clear();
                table.rows.add(response.productions).draw();
            },
            error: function(){
                alert("error");
            }
        });
    }

    let table = $('#datatable').DataTable();

    // display all productions
    getAllProductions();

    // display production by type
    $(".productionType").click(function() {
        let productionType = $(this).attr('id');

        if($(this).hasClass('activate'))
        {

            $(this).removeClass('activate');
            getAllProductions();

        }else {

            $(".productionType").removeClass('activate');
            $(this).addClass('activate');

            getProductionsByType(productionType);
        }
    });
});