var a = "";
var b = "";
var c = "";
$(document).ready(function(){
    $(".productionType").click(function() {
        productionType = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: '/productions-tab-change',
            data: {productionType},
            success: function(response) {

                console.log(JSON.parse(response))
                b = JSON.parse(response);
                /*console.log(jQuery.parseJSON(response));
                console.log(JSON.parse(response));*/
            },
            error: function(){
                alert("error");
            }
        });
    });
});