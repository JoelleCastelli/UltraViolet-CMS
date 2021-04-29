$(document).ready(function(){
    $(".productionType").click(function() {
        productionType = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: '/productions-tab-change',
            data: {productionType},
            success: function(response) {
                console.log(response)
            },
            error: function(){
                alert("error");
            }
        });
    });
});