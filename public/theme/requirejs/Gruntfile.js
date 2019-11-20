module.exports = function (grunt) {
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
    });
    grunt.loadNpmTasks("grunt-sass");
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask("buildcss", ["sass"]);
};
