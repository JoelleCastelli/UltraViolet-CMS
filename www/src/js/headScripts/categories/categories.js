$(document).ready( function () {
    /* BUILD DATATABLES */
    let table = $('#datatable').DataTable( {
        "order": [],
        "autoWidth": false,
        responsive: true,
        columns: [
            { data: 'Nom' },
            { data: 'Position' },
            { data: 'Actions' }
        ],

        columnDefs: [
            {
                targets: 2, // Actions column
                data: "name",
                searchable: false,
                orderable: false,
                width: '5%'
            },
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

    /* DISPLAY CATEGORIES */
    getCategoriesByType('visible');
    $("#visible").addClass('active');

    // Display different types on filtering button click
    $(".filtering-btn").click(function() {
        $(".filtering-btn").removeClass('active');
        $(this).addClass('active');
        getCategoriesByType(this.id);
    });

    function getCategoriesByType(categoryType) {
        $.ajax({
            type: 'POST',
            url: callRoute('categories_data'),
            data: { categoryType },
            dataType: 'json',
            async: false,
            success: function(response) {
                table.clear();
                table.rows.add(response.categories).draw();
            },
            error: function(response) {
                $('.header').after(errorServerJS);
            }
        });
    }

    /* DELETING CATEGORY */
    table.on('click', '.delete', function () {
        if (confirm('Êtes-vous sûr.e de vouloir supprimer cette catégorie ?')) {
            let categoryId = this.id.substring(this.id.lastIndexOf('-') + 1);
            let row = table.row($(this).parents('tr'));
            $.ajax({
                type: 'POST',
                url: callRoute('category_delete'),
                data: { categoryId: categoryId },
                dataType: 'json',
                success: function(response) {
                    if (response['success'])
                        row.remove().draw();
                    else
                        alert(response['message']);
                },
                error: function() {
                    console.log("Erreur dans la suppression de la production ID " + categoryId);
                }
            });
        }
    });

});