require(['c3', 'jquery'], function (c3, $) {
    $(document).ready(function () {       

        var chart = c3.generate({
            bindto: '#chart-donut', // id of chart wrapper
            data: {
                columns: [
                    // each columns data
                    ['data1', 63],
                    ['data2', 37]
                ],
                type: 'donut', // default type of chart
                colors: {
                    'data1': buzzer.colors["green"],
                    'data2': buzzer.colors["green-light"]
                },
                names: {
                    // name of each serie
                    'data1': 'Maximum',
                    'data2': 'Minimum'
                }
            },
            axis: {
            },
            legend: {
                show: false, //hide legend
            },
            padding: {
                bottom: 0,
                top: 0
            },
        });

        var chart = c3.generate({
            bindto: '#chart-pie', // id of chart wrapper
            data: {
                columns: [
                    // each columns data
                    ['data1', 63],
                    ['data2', 44],
                    ['data3', 12],
                    ['data4', 14]
                ],
                type: 'pie', // default type of chart
                colors: {
                    'data1': buzzer.colors["blue-darker"],
                    'data2': buzzer.colors["blue"],
                    'data3': buzzer.colors["blue-light"],
                    'data4': buzzer.colors["blue-lighter"]
                },
                names: {
                    // name of each serie
                    'data1': 'A',
                    'data2': 'B',
                    'data3': 'C',
                    'data4': 'D'
                }
            },
            axis: {
            },
            legend: {
                show: false, //hide legend
            },
            padding: {
                bottom: 0,
                top: 0
            },
        });

        var chart = c3.generate({
            bindto: '#chart-bg-users-1',
            padding: {
                bottom: -10,
                left: -1,
                right: -1
            },
            data: {
                names: {
                    data1: 'Users online'
                },
                columns: [
                    ['data1', 30, 40, 10, 40, 12, 22, 40]
                ],
                type: 'area'
            },
            legend: {
                show: false
            },
            transition: {
                duration: 0
            },
            point: {
                show: false
            },
            tooltip: {
                format: {
                    title: function (x) {
                        return '';
                    }
                }
            },
            axis: {
                y: {
                    padding: {
                        bottom: 0,
                    },
                    show: false,
                    tick: {
                        outer: false
                    }
                },
                x: {
                    padding: {
                        left: 0,
                        right: 0
                    },
                    show: false
                }
            },
            color: {
                pattern: ['#467fcf']
            }
        });

        var chart = c3.generate({
            bindto: '#chart-bg-users-2',
            padding: {
                bottom: -10,
                left: -1,
                right: -1
            },
            data: {
                names: {
                    data1: 'Users online'
                },
                columns: [
                    ['data1', 30, 40, 10, 40, 12, 22, 40]
                ],
                type: 'area'
            },
            legend: {
                show: false
            },
            transition: {
                duration: 0
            },
            point: {
                show: false
            },
            tooltip: {
                format: {
                    title: function (x) {
                        return '';
                    }
                }
            },
            axis: {
                y: {
                    padding: {
                        bottom: 0,
                    },
                    show: false,
                    tick: {
                        outer: false
                    }
                },
                x: {
                    padding: {
                        left: 0,
                        right: 0
                    },
                    show: false
                }
            },
            color: {
                pattern: ['#e74c3c']
            }
        });

        var chart = c3.generate({
            bindto: '#chart-bg-users-3',
            padding: {
                bottom: -10,
                left: -1,
                right: -1
            },
            data: {
                names: {
                    data1: 'Users online'
                },
                columns: [
                    ['data1', 30, 40, 10, 40, 12, 22, 40]
                ],
                type: 'area'
            },
            legend: {
                show: false
            },
            transition: {
                duration: 0
            },
            point: {
                show: false
            },
            tooltip: {
                format: {
                    title: function (x) {
                        return '';
                    }
                }
            },
            axis: {
                y: {
                    padding: {
                        bottom: 0,
                    },
                    show: false,
                    tick: {
                        outer: false
                    }
                },
                x: {
                    padding: {
                        left: 0,
                        right: 0
                    },
                    show: false
                }
            },
            color: {
                pattern: ['#5eba00']
            }
        });

        var chart = c3.generate({
            bindto: '#chart-bg-users-4',
            padding: {
                bottom: -10,
                left: -1,
                right: -1
            },
            data: {
                names: {
                    data1: 'Users online'
                },
                columns: [
                    ['data1', 30, 40, 10, 40, 12, 22, 40]
                ],
                type: 'area'
            },
            legend: {
                show: false
            },
            transition: {
                duration: 0
            },
            point: {
                show: false
            },
            tooltip: {
                format: {
                    title: function (x) {
                        return '';
                    }
                }
            },
            axis: {
                y: {
                    padding: {
                        bottom: 0,
                    },
                    show: false,
                    tick: {
                        outer: false
                    }
                },
                x: {
                    padding: {
                        left: 0,
                        right: 0
                    },
                    show: false
                }
            },
            color: {
                pattern: ['#f1c40f']
            }
        });
    });
});

require(['knob', 'jquery'], function (knob, $) {

    $('.knob').knob({
        draw: function () {
        }
    });

    $('.knob2').knob({
        'format': function (value) {
            return value + '%';
        }
    });
});

require(['jvectormap', 'jquery'], function (jvectormap, $) {

    if ($('#world-map-markers').length > 0) {

        $('#world-map-markers').vectorMap(
            {
                map: 'world_mill_en',
                backgroundColor: 'transparent',
                borderColor: '#fff',
                borderOpacity: 0.25,
                borderWidth: 0,
                color: '#e6e6e6',
                regionStyle: {
                    initial: {
                        fill: '#e9ecef'
                    }
                },

                markerStyle: {
                    initial: {
                        r: 5,
                        'fill': '#fff',
                        'fill-opacity': 1,
                        'stroke': '#000',
                        'stroke-width': 1,
                        'stroke-opacity': 0.4
                    },
                },

                markers: [{
                    latLng: [21.00, 78.00],
                    name: 'INDIA : 350'

                },
                {
                    latLng: [-33.00, 151.00],
                    name: 'Australia : 250'

                },
                {
                    latLng: [36.77, -119.41],
                    name: 'USA : 250'

                },
                {
                    latLng: [55.37, -3.41],
                    name: 'UK   : 250'

                },
                {
                    latLng: [25.20, 55.27],
                    name: 'UAE : 250'

                }],

                series: {
                    regions: [{
                        values: {
                            "US": '#17a2b8',
                            "SA": '#28a745',
                            "AU": '#de5d83',
                            "IN": '#fd9644',
                            "GB": '#a55eea',
                        },
                        attribute: 'fill'
                    }]
                },
                hoverOpacity: null,
                normalizeFunction: 'linear',
                zoomOnScroll: false,
                scaleColors: ['#000000', '#000000'],
                selectedColor: '#000000',
                selectedRegions: [],
                enableZoom: false,
                hoverColor: '#fff',
            });
    }

});

require(['sparkline', 'jquery'], function (sparkline, $) {

    $('.sale_Weekly').sparkline('html', {
        type: 'bar',
        height: '30px',
        barSpacing: 5,
        barWidth: 5,
        barColor: '#007bff',
    });
    $('.sale_Monthly').sparkline('html', {
        type: 'bar',
        height: '30px',
        barSpacing: 6,
        barWidth: 2,
        barColor: '#28a745',
    });
});

require(['morrisjs', 'jquery'], function (morrisjs, $) {
    var line = new Morris.Area({
        element: 'e_area_chart',
        data: [
            {
                period: '2012',
                iphone: 10,
                ipad: 5,
                itouch: 7
            }, {
                period: '2013',
                iphone: 35,
                ipad: 89,
                itouch: 45
            }, {
                period: '2014',
                iphone: 25,
                ipad: 15,
                itouch: 102
            }, {
                period: '2015',
                iphone: 80,
                ipad: 12,
                itouch: 7
            }, {
                period: '2016',
                iphone: 30,
                ipad: 32,
                itouch: 148
            }, {
                period: '2017',
                iphone: 25,
                ipad: 127,
                itouch: 40
            }, {
                period: '2018',
                iphone: 98,
                ipad: 10,
                itouch: 26
            }


        ],
        lineColors: ['#36c7e2', '#fecd42', '#936faf'],
        xkey: 'period',
        ykeys: ['iphone', 'ipad', 'itouch'],
        labels: ['Site A', 'Site B', 'Site C'],
        pointSize: 0,
        lineWidth: 0,
        resize: true,
        fillOpacity: 0.8,
        behaveLikeLine: true,
        gridLineColor: '#dee2e6',
        hideHover: 'auto'
    });
});
