/*jshint node:true */
module.exports = function ( grunt ) {
	grunt.loadNpmTasks( 'grunt-banana-checker' );
	grunt.loadNpmTasks( 'grunt-jsonlint' );
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-jscs' );

	grunt.initConfig( {
		banana: {
			all: 'i18n/'
		},
		jsonlint: {
			all: [
				'**/*.json',
				'!node_modules/**',
				'!vendor/**'
			]
		},
		jshint: {
			options: {
				jshintrc: true
			},
			all: [
				'**/*.js',
				'modules/**/*.js',
				'!modules/jquery/*.js',
				'!vendor/**'
			]
		},
		jscs: {
			src: '<%= jshint.all %>'
		}
	} );

	grunt.registerTask( 'test', [ 'jsonlint', 'banana', 'jshint', 'jscs' ] );
	grunt.registerTask( 'default', 'test' );
};
