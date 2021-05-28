$(document).ready(function () {

    /* BUILD DATATABLES */
    let table = $('#datatable').DataTable({

        // All columns    
        columns: [
            {
                data: 'Nom de la page',
            },
            {
                data: 'URL de la page',

            },
            {
                data: 'Ordre',
                width: "10%"

            },
            {
                data: 'Nombre d\'articles',
                width: "15%"

            },
            {
                data: 'Visibilité',
                width: "10%"
            },
            {
                data: 'Actions',
                width: "10%"
            }
        ],

        // Column Actions 
        columnDefs: [{
            targets: 5,
            data: "Actions",
            searchable: false,
            orderable: false
        }],

        language: {
            "sEmptyTable": "Aucune donnée disponible dans le tableau",
            "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
            "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
            "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
            "sInfoThousands": ",",
            "sLengthMenu": "Afficher _MENU_ éléments",
            "sLoadingRecords": "Chargement...",
            "sProcessing": "Traitement...",
            "sSearch": "",
            "sZeroRecords": "Aucun élément correspondant trouvé",
            "oPaginate": {
                "sFirst": "Premier",
                "sLast": "Dernier",
                "sNext": "Suivant",
                "sPrevious": "Précédent"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
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
    // On start, display published pages
    getPagesByType('published');
    $("#published").addClass('active');

    // Display different types on filtering button click
    $(".filtering-btn").click(function () {
        console.log('yes');
        $(".filtering-btn").removeClass('active');
        $(this).addClass('active');
        getPagesByType(this.id)
    });

    function getPagesByType(pageType) {
        $.ajax({
            type: 'POST',
            url: '/admin/pages/pages-data',
            data: { pageType },
            dataType: 'json',
            success: function (response) {
                table.clear();
                table.rows.add(response.pages).draw();
            },
            error: function () {
                console.log("Erreur dans la récupération des pages de type " + pageType);
            }
        });
    }

    /* FORM */

    $('.form-add-page').submit(function (event) {
        event.preventDefault();

        if ($('#add-page-modal').hasClass('modal-visible')) {

            console.log('form submit lol');
            console.log($(this).serialize());
            console.log($(this));

            $.ajax({
                type: 'POST',
                url: '/admin/pages/creation',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    console.log('success');
                    console.log(response);

                    if (response['success'])
                        $('#add-page-modal .container-message').html(successMessageForm(response['message']));
                    else 
                        $('#add-page-modal .container-message').html(errorMessageForm(response['message']));

                },
                error: function (data) {
                    console.log('error');
                    console.log(data);

                }
            });
        } 
    })

    function successMessageForm(message) {
        return '<p class="success-message-form">' + 
                    '<i class="fas fa-check icon-message-form"></i>' +
                    message +
                '</p>';
    }

    function errorMessageForm(message) {
        return '<p class="error-message-form">' +
                    '<i class="fas fa-times icon-message-form"></i>' +
                    message +
                '</p>';
    }
});