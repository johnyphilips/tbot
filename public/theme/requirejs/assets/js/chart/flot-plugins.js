require.config({
    shim: {
        'flot': ['jquery'],
        'flotresize': ['flot', 'jquery'],
        'flotpie': ['flot', 'jquery'],
        'flotcategories': ['flot', 'jquery'],
        'flottime': ['flot', 'jquery'],
    },
    paths: {

        'flot': '../assets/plugins/flot-charts/flot',
        'flotresize': '../assets/plugins/flot-charts/flot.resize',
        'flotpie': '../assets/plugins/flot-charts/flot.pie',
        'flotcategories': '../assets/plugins/flot-charts/flot.categories',
        'flottime': '../assets/plugins/flot-charts/flot.time',
    }
});
