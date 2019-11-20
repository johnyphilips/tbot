module.exports = function(grunt) {
    grunt.initConfig({
		sass: {			
            dist: {
				options: {
					outputStyle: 'compressed'
				},
                files: [{
                    'assets/css/main.css': 'assets/scss/main.scss', 	                        /* 'All main SCSS' */
				}]
            }
        },  
        uglify: {
            my_target: {
              files: {
                    'assets/bundles/libscripts.bundle.js': ['../assets/plugins/jquery/jquery-3.3.1.min.js','../assets/plugins/bootstrap/js/popper.min.js','../assets/plugins/bootstrap/js/bootstrap.js'], /* main js*/
                    
                    'assets/bundles/vendorscripts.bundle.js': ['../assets/plugins/sparkline/sparkline.js','assets/js/vendors/circle-progress.min.js'], /* coman js*/
                    
                    'assets/bundles/mainscripts.bundle.js':['assets/js/core.js'], /*coman js*/

                    'assets/bundles/c3.bundle.js':['../assets/plugins/charts-c3/js/c3.min.js', '../assets/plugins/charts-c3/js/d3.v3.min.js'],
                    'assets/bundles/fullcalendar.bundle.js':['../assets/plugins/fullcalendar/moment.min.js', '../assets/plugins/fullcalendar/fullcalendar.min.js'],
                    'assets/bundles/summernote.bundle.js':['../assets/plugins/summernote/dist/summernote.js'],
                    'assets/bundles/sweetalert.bundle.js':['../assets/plugins/sweetalert/sweetalert.min.js'],
                    'assets/bundles/nestable.bundle.js':['../assets/plugins/nestable/jquery.nestable.js'],
                    'assets/bundles/chartjs.bundle.js':[
                        '../assets/plugins/chartjs/chartist.min.js',
                        '../assets/plugins/chartjs/chartist-plugin-tooltip.min.js',
                        '../assets/plugins/chartjs/chartist-plugin-axistitle.min.js',
                        '../assets/plugins/chartjs/chartist-plugin-legend.js',
                        '../assets/plugins/chartjs/Chart.bundle.js'], /*chartist js*/
                    
                    'assets/bundles/morris.bundle.js':['../assets/plugins/raphael/raphael.min.js','../assets/plugins/morrisjs/morris.min.js'],
                    'assets/bundles/flot.bundle.js':['../assets/plugins/flot-charts/flot.js','../assets/plugins/flot-charts/flot.resize.js','../assets/plugins/flot-charts/flot.pie.js','../assets/plugins/flot-charts/flot.categories.js','../assets/plugins/flot-charts/flot.time.js'],
                    'assets/bundles/knobjs.bundle.js':['../assets/plugins/knobjs/knob.min.js'],
                    
                    
                    'assets/bundles/jvectormap1.bundle.js': ['../assets/plugins/jvectormap/jvectormap-2.0.3.min.js','../assets/plugins/jvectormap/jvectormap-world.js','../assets/plugins/jvectormap/jvectormap-in.js','../assets/plugins/jvectormap/jvectormap-us.js','../assets/plugins/jvectormap/jvectormap-au.js','../assets/plugins/jvectormap/jvectormap-uk.js'],
                    'assets/bundles/jvectormap2.bundle.js':['assets/js/map/jquery-jvectormap-2.0.3.min.js', 'assets/js/map/jquery-jvectormap-de-merc.js', 'assets/js/map/jquery-jvectormap-world-mill.js'],
                    'assets/bundles/selectize.bundle.js':['assets/js/vendors/selectize.min.js'],
                    'assets/bundles/markdown.bundle.js':['../assets/plugins/markdown/markdown.js','../assets/plugins/markdown/to-markdown.js','../assets/plugins/markdown/bootstrap-markdown.js'],
                    'assets/bundles/dataTables.bundle.js':[
                        '../assets/plugins/datatable/jquery.dataTables.min.js',
                        '../assets/plugins/datatable/dataTables.bootstrap4.min.js',
                        '../assets/plugins/datatable/buttons/dataTables.buttons.min.js',
                        '../assets/plugins/datatable/buttons/buttons.bootstrap4.min.js',
                        '../assets/plugins/datatable/buttons/buttons.colVis.min.js',
                        '../assets/plugins/datatable/buttons/buttons.html5.min.js',
                        '../assets/plugins/datatable/buttons/buttons.print.min.js'], /*chartist js*/
                  }
              }
          }                      
    });
    grunt.loadNpmTasks("grunt-sass");
    grunt.loadNpmTasks('grunt-contrib-uglify');
    
    grunt.registerTask("buildcss", ["sass"]);
    grunt.registerTask("buildjs", ["uglify"]);
};
