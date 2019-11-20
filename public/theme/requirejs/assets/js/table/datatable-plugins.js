require.config({
    shim: {
        'bootstrap': {
            deps: ['jquery']
        },
    },


    paths: {
        'datatables.net': '../assets/plugins/datatable/jquery.dataTables.min',
        'datatables.bootstrap': '../assets/plugins/datatable/dataTables.bootstrap4.min',

        'datatables.buttons': '../assets/plugins/datatable/buttons/dataTables.buttons.min',
        'datatables.bootstrap4': '../assets/plugins/datatable/buttons/buttons.bootstrap4.min',
        'datatables.colVis': '../assets/plugins/datatable/buttons/buttons.colVis.min',
        'datatables.html5': '../assets/plugins/datatable/buttons/buttons.html5.min',
        'datatables.print': '../assets/plugins/datatable/buttons/buttons.print.min',
    }
});