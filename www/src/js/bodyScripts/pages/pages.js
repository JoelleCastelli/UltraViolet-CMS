var a = 0;
$(document).ready(function() {

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
    $(".filtering-btn").click(function() {
        $(".filtering-btn").removeClass('active');
        table.column([4]).visible(true);
        $(this).addClass('active');
        if (this.id !== "published") {
            table.column([4]).visible(false);
        }
        getPagesByType(this.id);
    });

    function getPagesByType(pageType) {
        $.ajax({
            type: 'POST',
            url: callRoute('pages-data'),
            data: { pageType },
            dataType: 'json',
            async: false,
            success: function(response) {
                table.clear();
                table.rows.add(response.pages).draw();
            },
            error: function(response) {
                $('.header').after(errorServerJS);
            }
        });
    }

    /* FORM ADD PAGE*/
    $('.form-add-page').submit(function(event) {
        event.preventDefault();

        if ($('#add-page-modal').hasClass('modal-visible')) {

            $.ajax({
                type: 'POST',
                url: callRoute('page_creation'),
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response['success']) {

                        let button = "<button class='btn add-new-page'>" +
                            "Créer une nouvelle page" +
                            "</button>";

                        $('#add-page-modal .content-modal').hide();
                        $('#add-page-modal .content-modal .form-add-page')[0].reset()
                        $('#add-page-modal .footer-modal').prepend(button);
                        $('#add-page-modal .footer-modal').prepend(successMessageForm(response['message']));
                    }

                    else
                        $('#add-page-modal .container-message').html(errorMessageForm(response['message']));

                },
                error: function(response, statut, erreur) {
                    $('#add-page-modal .container-message').html(errorMessageForm(errorServerJS));
                }
            });
        }
    })
});

/* ADD NEW PAGE WHEN MODAL ALREADY OPEN */
$('#add-page-modal').click(function(event) {
    let targetElement = event.target;
    let selector = 'add-new-page';

    if (targetElement != null) {

        a = targetElement;

        if ($(a).hasClass(selector)) {
            console.log("clickk selector");
            $('#add-page-modal .content-modal').show();
            $('#add-page-modal .add-new-page').remove();
            $('#add-page-modal .error-message-form').remove();
            $('#add-page-modal .success-message-form').remove();
            return;
        }
    }
});