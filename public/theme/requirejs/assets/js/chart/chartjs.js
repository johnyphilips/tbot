
require(['ChartJs', 'jquery'], function (ChartJs, $) {

    $(function () {
        new Chart(document.getElementById("line_chart").getContext("2d"), getChartJs('line'));
        new Chart(document.getElementById("bar_chart").getContext("2d"), getChartJs('bar'));
        new Chart(document.getElementById("radar_chart").getContext("2d"), getChartJs('radar'));
        new Chart(document.getElementById("pie_chart").getContext("2d"), getChartJs('pie'));
        new Chart(document.getElementById("doughnut_chart").getContext("2d"), getChartJs('doughnut'));
        new Chart(document.getElementById("polar_area_chart").getContext("2d"), getChartJs('polarArea'));
        new Chart(document.getElementById("stacked_group").getContext("2d"), getChartJs('stacked'));
        new Chart(document.getElementById("combo_bar_line").getContext("2d"), getChartJs('combo_bar'));
    });

    function getChartJs(type) {
        var config = null;

        if (type === 'line') {
            config = {
                type: 'line',
                data: {
                    labels: ["January", "February", "March", "April", "May", "June", "July"],
                    datasets: [{
                        label: "My First dataset",
                        data: [28, 58, 39, 45, 30, 55, 68],
                        borderColor: 'rgba(241,95,121, 0.2)',
                        backgroundColor: 'rgba(241,95,121, 0.5)',
                        pointBorderColor: 'rgba(241,95,121, 0.3)',
                        pointBackgroundColor: 'rgba(241,95,121, 0.2)',
                        pointBorderWidth: 1
                    }, {
                        label: "My Second dataset",
                        data: [40, 28, 50, 48, 63, 39, 41],
                        borderColor: 'rgba(140,147,154, 0.2)',
                        backgroundColor: 'rgba(140,147,154, 0.2)',
                        pointBorderColor: 'rgba(140,147,154, 0)',
                        pointBackgroundColor: 'rgba(140,147,154, 0.9)',
                        pointBorderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                }
            }
        }
        else if (type === 'bar') {
            config = {
                type: 'bar',
                data: {
                    labels: ["January", "February", "March", "April", "May", "June", "July"],
                    datasets: [{
                        label: "My First dataset",
                        data: [28, 48, 40, 19, 86, 27, 90],
                        backgroundColor: '#26c6da',
                        strokeColor: "rgba(255,118,118,0.1)",
                    }, {
                        label: "My Second dataset",
                        data: [10, 30, 80, 61, 26, 75, 40],
                        backgroundColor: '#8a8a8b',
                        strokeColor: "rgba(255,118,118,0.1)",
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                }
            }
        }
        else if (type === 'radar') {
            config = {
                type: 'radar',
                data: {
                    labels: ["January", "February", "March", "April", "May", "June", "July"],
                    datasets: [
                        {
                            label: "My First dataset",
                            data: [65, 25, 90, 81, 56, 55, 40],
                            borderColor: 'rgba(241,95,121, 0.8)',
                            backgroundColor: 'rgba(241,95,121, 0.1)',
                            pointBorderColor: 'rgba(241,95,121, 0)',
                            pointBackgroundColor: 'rgba(241,95,121, 0.8)',
                            pointBorderWidth: 1
                        }, {
                            label: "My Second dataset",
                            data: [72, 48, 40, 19, 96, 27, 100],
                            borderColor: 'rgba(140,147,154, 0.8)',
                            backgroundColor: 'rgba(140,147,154, 0.1)',
                            pointBorderColor: 'rgba(140,147,154, 0)',
                            pointBackgroundColor: 'rgba(140,147,154, 0.8)',
                            pointBorderWidth: 1
                        }
                    ]
                },

                options: {
                    responsive: true,
                    legend: false
                }
            }
        }
        else if (type === 'pie') {
            config = {
                type: 'pie',
                data: {
                    datasets: [{
                        data: [150, 53, 121, 87, 45],
                        backgroundColor: [
                            "#ff6384",
                            "#ff9f40",
                            "#ffcd56",
                            "#4bc0c0",
                            "#059bff"
                        ],
                    }],
                    labels: [
                        'Red',
                        'Orange',
                        'Yellow',
                        'Cyan',
                        'Blue'
                    ]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            }
        }
        else if (type === 'doughnut') {
            config = {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [150, 53, 121, 87, 45],
                        backgroundColor: [
                            "#ff6384",
                            "#ff9f40",
                            "#ffcd56",
                            "#4bc0c0",
                            "#059bff"
                        ],
                    }],
                    labels: [
                        'Red',
                        'Orange',
                        'Yellow',
                        'Cyan',
                        'Blue'
                    ]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'bottom',
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            }
        }
        else if (type === 'polarArea') {
            var config = {
                type: 'polarArea',
                data: {
                    datasets: [{
                        data: [150, 53, 121, 87, 45],
                        backgroundColor: [
                            "#ff6384",
                            "#ff9f40",
                            "#ffcd56",
                            "#4bc0c0",
                            "#059bff"
                        ],
                        label: 'My dataset' // for legend
                    }],
                    labels: [
                        'Red',
                        'Orange',
                        'Yellow',
                        'Cyan',
                        'Blue'
                    ]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                    },
                    scale: {
                        ticks: {
                            beginAtZero: true
                        },
                        reverse: false
                    },
                    animation: {
                        animateRotate: false,
                        animateScale: true
                    }
                }
            };
        }
        else if (type === 'stacked') {
            config = {
                type: 'bar',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                    datasets: [{
                        label: 'Dataset 1',
                        stack: 'Stack 0',
                        data: [25, 31, 15, -12, 78, 24, 89],
                        backgroundColor: '#26c6da',
                    }, {
                        label: 'Dataset 2',
                        stack: 'Stack 0',
                        data: [27, 48, 40, 19, -48, 27, 90],
                        backgroundColor: '#ff9f40',
                    }, {
                        label: 'Dataset 3',
                        stack: 'Stack 1',
                        data: [28, -8, 34, 24, 86, 23, 70],
                        backgroundColor: '#8a8a8b',
                    }],
                },
                options: {
                    title: {
                        display: true,
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    scales: {
                        xAxes: [{
                            stacked: true,
                        }],
                        yAxes: [{
                            stacked: true
                        }]
                    }
                }
            };
        }
        else if (type === 'combo_bar') {
            config = {
                type: 'bar',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                    datasets: [{
                        type: 'line',
                        label: 'Dataset 1',
                        borderWidth: 2,
                        fill: false,
                        data: [25, 31, 15, -12, 78, 24, 89],
                        borderColor: '#26c6da',
                    }, {
                        type: 'bar',
                        label: 'Dataset 2',
                        borderColor: 'white',
                        borderWidth: 2,
                        data: [27, 48, 40, 19, -48, 27, 90],
                        backgroundColor: '#ff9f40',
                    }, {
                        type: 'bar',
                        label: 'Dataset 3',
                        data: [28, -8, 34, 24, 86, 23, 70],
                        backgroundColor: '#059bff',
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: true
                    }
                }
            };
        }
        return config;
    }
});
