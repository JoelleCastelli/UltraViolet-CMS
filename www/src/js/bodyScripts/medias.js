var a = 0;

function getAllMedias() {
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
}
function getMediasByType(mediaTypes) {
    console.log('enter function getmediastype');
    console.log(mediaTypes);
    $.ajax({
        type: 'POST',
        url: '/admin/medias/medias-data',
        data: {mediaTypes: mediaTypes },
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
}

$(document).ready( function () {

   getAllMedias();

    $('.media-type').click(function () {

        let types = $('.media-type:checked');
        a = types;
        mediaTypes = types.map(function() { return $(this).val() });
        a = mediaTypes;
        getMediasByType(mediaTypes);

    });

})