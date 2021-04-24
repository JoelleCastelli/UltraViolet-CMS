$(document).ready(function(){
    $(".articleState").click(function() {
        articleState = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: '/tab-change',
            data: {articleState},
            success: function(response) {
                console.log(response)
            },
            error: function(){
                alert("error");
            }
        });
    });
});