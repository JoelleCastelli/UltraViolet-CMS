$(document).ready(function() {

    // On page reload, reenable fields if series
    if($('input[name="productionType"]:checked').val() === 'series') {
        $("input[name='seasonNb']").prop('disabled', false);
        if($('input[name="seasonNb"]').val() !== '') {
            $("input[name='episodeNb']").prop('disabled', false);
        }
    }

    $("input[name='productionType']").change(function(){
        if($(this).val() === "movie") {
            $("input[name='seasonNb']").prop('disabled', true);
            $("input[name='episodeNb']").prop('disabled', true);
        } else {
            $("input[name='seasonNb']").prop('disabled', false);
        }
    });

    $(document).on('input', '#seasonNb', function() {
        if($(this).val() !== '') {
            $("input[name='episodeNb']").prop('disabled', false);
        } else {
            $("input[name='episodeNb']").prop('disabled', true);
        }
    })
    
    $("#productionPreviewRequest").click(function() {
        $.ajax({
            type: 'POST',
            url: callRoute('productions_show_preview'),
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
