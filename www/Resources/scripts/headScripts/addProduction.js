$(document).ready(function(){
    $("#productionPreviewRequest").click(function() {
        $.ajax({
            type: 'POST',
            url: '/tmdb-request',
            data: $('#formAddProductionTmdb').serialize(),
            success: function(response) {
                $("#production-preview").html(response);
            },
            error: function(){
                alert("error");
            }
        });
    });
});
