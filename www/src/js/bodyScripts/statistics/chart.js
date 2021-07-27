$(document).ready(function () {

    var chart = document.getElementById('viewResults');
    let type = chart.dataset.type
    getViewsStats()
    function getViewsStats() {
        $.ajax({
            type: "GET",
            url: '/admin/views-stats',
            async: true,
            dataType: 'json',
            success: function(data) {
                var ctx = document.getElementById('viewResults').getContext('2d');
                var viewResults = new Chart(ctx, {
                    type: type,
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Nombres de vues',
                            data: data.data,
                            fill: false,
                            borderColor: 'rgb(95, 46, 234)',
                            backgroundColor: 'rgb(95, 46, 234)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                min: 0,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            },
            error: function(data) {
                return data = "";
            }
        });
    }
})