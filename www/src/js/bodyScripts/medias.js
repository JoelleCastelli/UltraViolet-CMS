$(document).ready( function () {

    // get all medias
    $.ajax({
        type: 'POST',
        url: '/admin/medias/medias-data',
        dataType: 'json',
        success: function(response) {

            console.log(response);
            /*table.clear();
            table.rows.add(response.productions).draw();*/
        },
        error: function(){
            alert("error");
        }
    });

    // get medias by type
    $('.media-type').click(function () {

        $.ajax({
            type: 'POST',
            url: '/admin/medias/medias-data',
            dataType: 'json',
            success: function(response) {

                console.log(response);
                /*table.clear();
                table.rows.add(response.productions).draw();*/
            },
            error: function(){
                alert("error");
            }
        });

    });

})