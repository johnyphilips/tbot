require.config({
	shim: {
	    'raphael':                  ['jquery'],
        'morrisjs':                 ['raphael', 'jquery'],
	},
	paths: {
  
		'raphael':                  '../assets/plugins/raphael/raphael.min',
        'morrisjs':                 '../assets/plugins/morrisjs/morris.min',
	}
});
  