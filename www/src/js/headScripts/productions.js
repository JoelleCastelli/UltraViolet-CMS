var a = "";

$(document).ready(function(){
    function getAllProductions()
    {
        $.ajax({
            type: 'POST',
            url: '/productions-data',
            dataType: 'json',
            success: function(response) {
                b = response;

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
            url: '/productions-data',
            data: {productionType},
            dataType: 'json',
            success: function(response) {
                b = response;
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