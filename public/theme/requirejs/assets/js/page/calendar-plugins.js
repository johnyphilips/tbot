require.config({
    shim: {
        'moment': ['jquery'],
        'fullcalendar': ['moment', 'jquery'],
    },
    paths: {
        'moment': '../assets/plugins/fullcalendar/moment.min',
        'fullcalendar': '../assets/plugins/fullcalendar/fullcalendar.min',
    }
});
