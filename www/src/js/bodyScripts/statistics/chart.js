$(document).ready(function () {

    var chart = document.getElementById('viewResults');

    let datas = chart.dataset.data
    let type = chart.dataset.type

    console.log(datas);
    console.log(type);

    function getViewsStats() {
        $.ajax({
            type: "GET",
            url: '/admin/views-stats',
            async: true,
            success: function(responce) {
                data = responce;
                console.log(data);
                console.log('yes');
            },
            error: function(responce) {
                data = "";
                console.log('null');
            }
        });
        return data;
    }

    console.log(getViewsStats())

    var ctx = document.getElementById('viewResults').getContext('2d');
    var viewResults = new Chart(ctx, {
        type: type,
        data: {
            labels: datas.labels,
            datasets: [{
                label: 'Nombres de vues',
                data: datas.data,
                fill: false,
                borderColor: 'rgb(95, 46, 234)',
                backgroundColor: 'rgb(95, 46, 234)',
                tension: 0.1
            }]
        }
    });
})
