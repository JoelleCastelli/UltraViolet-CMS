var a = "";
var b = "";
var c = "";

var array = [

    [
        "BIZZARE",
        "System Architect",
        "Edinburgh",
        "5421",
        "2011/04/25",
        "$3,120"
    ],
    [
        "MDR",
        "System Architect",
        "Edinburgh",
        "5421",
        "2011/04/25",
        "$3,120"
    ],[
        "Tiger Nixon",
        "System Architect",
        "Edinburgh",
        "5421",
        "2011/04/25",
        "$3,120"
    ],[
        "Tiger Nixon",
        "System Architect",
        "Edinburgh",
        "5421",
        "2011/04/25",
        "$3,120"
    ],
    [
        "Tiger Nixon",
        "System Architect",
        "Edinburgh",
        "5421",
        "2011/04/25",
        "$3,120"
    ],[
        "Tiger Nixon",
        "System Architect",
        "Edinburgh",
        "5421",
        "2011/04/25",
        "$3,120"
    ],
    [
        "Tiger Nixon",
        "System Architect",
        "Edinburgh",
        "5421",
        "2011/04/25",
        "$3,120"
    ],
    [
        "Tiger Nixon",
        "System Architect",
        "Edinburgh",
        "5421",
        "2011/04/25",
        "$3,120"
    ],
    [
        "Tiger Nixon",
        "System Architect",
        "Edinburgh",
        "5421",
        "2011/04/25",
        "$3,120"
    ],
    [
        "Tiger Nixon",
        "System Architect",
        "Edinburgh",
        "5421",
        "2011/04/25",
        "$3,120"
    ],
    [
        "Tiger Nixon",
        "System Architect",
        "Edinburgh",
        "5421",
        "2011/04/25",
        "$3,120"
    ],
    [
        "Tiger Nixon",
        "System Architect",
        "Edinburgh",
        "5421",
        "2011/04/25",
        "$3,120"
    ],
    [
        "Tiger Nixon",
        "System Architect",
        "Edinburgh",
        "5421",
        "2011/04/25",
        "$3,120"
    ],
    [
        "Tiger Nixon",
        "System Architect",
        "Edinburgh",
        "5421",
        "2011/04/25",
        "$3,120"
    ],
    [
        "Tiger Nixon",
        "System Architect",
        "Edinburgh",
        "5421",
        "2011/04/25",
        "$3,120"
    ],
    [
        "Tiger Nixon",
        "System Architect",
        "Edinburgh",
        "5421",
        "2011/04/25",
        "$3,120"
    ],[
        "Tiger Nixon",
        "System Architect",
        "Edinburgh",
        "5421",
        "2011/04/25",
        "$3,120"
    ],
    [
        "DEMS",
        "System Architect",
        "Edinburgh",
        "5421",
        "2011/04/25",
        "$3,120"
    ],
    [
        "VIE",
        "System Architect",
        "Edinburgh",
        "5421",
        "2011/04/25",
        "$3,120"
    ],
    [
        "Tiger Nixon",
        "System Architect",
        "Edinburgh",
        "5421",
        "2011/04/25",
        "POIRE"
    ],





];

var objects = [
    {
        "Titre": "Tiger Nixon",
        "Titre original": "System Architect",
        "Date de sortie": "$3,120",
        "Durée": "2011/04/25",
        "Résumé": "Edinburgh",
        "Actions": "5421"
    },
    {
        "Titre": "Garretdect Winters",
        "Titre original": "Director",
        "Date de sortie": "$5,300",
        "Durée": "2011/07/25",
        "Résumé": "Edinburgh",
        "Actions": "8422"
    }
];
$(document).ready(function(){

    $.ajax({
        type: 'POST',
        url: '/productions-data',
        dataType: 'json',
        success: function(response) {
            table.clear();
            table.rows.add(response).draw();
        },
        error: function(){
            alert("error");
        }
    });

    let table = $('#datatable').DataTable();

    $(".productionType").click(function() {
        let productionType = $(this).attr('id');


        $.ajax({
            type: 'POST',
            url: '/productions-tab-change',
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
    });
});