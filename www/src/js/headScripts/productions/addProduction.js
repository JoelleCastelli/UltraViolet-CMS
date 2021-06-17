$(document).ready(function() {

    $("input[name='productionType']").change(function(){
        if($(this).val() === "movie") {
            $("input[name='seasonNb']").prop('disabled', true);
            $("input[name='episodeNb']").prop('disabled', true);
        } else {
            $("input[name='seasonNb']").prop('disabled', false);
            $("input[name='episodeNb']").prop('disabled', false)
        }
    });
    
    $("#productionPreviewRequest").click(function() {
        $.ajax({
            type: 'POST',
            url: '/admin/productions/tmdb-request',
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
