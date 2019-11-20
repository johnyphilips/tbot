require.config({
	shim: {
	'jvectormap':               ['jquery'],
	'jvectormap_in':            ['jvectormap','jquery'],
	'jvectormap_us':            ['jvectormap','jquery'],
	
	'vector_map':               ['jquery'],
        'vector_map_de':            ['vector_map', 'jquery'],
	'vector_map_world':         ['vector_map', 'jquery'],
	
	},
	paths: {
  
	'jvectormap':               '../assets/plugins/jvectormap/jvectormap.bundle',
	'jvectormap_in':            '../assets/plugins/jvectormap/jvectormap-in',
	'jvectormap_us':            '../assets/plugins/jvectormap/jvectormap-us',
	
	'vector_map':               'assets/js/map/jquery-jvectormap-2.0.3.min',
        'vector_map_de':            'assets/js/map/jquery-jvectormap-de-merc',
	'vector_map_world':         'assets/js/map/jquery-jvectormap-world-mill', 
	
	}
});
  