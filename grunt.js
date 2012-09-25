module.exports = function(grunt)
{
	/* define css rules */

	grunt.cssRules =
	{
		'adjoining-classes': false,
		'box-model': false,
		'box-sizing': false,
		'compatible-vendor-prefixes': false,
		'duplicate-background-images': false,
		'gradients': false,
		'fallback-colors': false,
		'text-indent': false,
		'unique-headings': false,
		'outline-none': false,
		'qualified-headings': false
	};

	/* config grunt */

	grunt.initConfig({
		lint:
		{
			all: ['grunt.js', 'scripts/*.js', 'templates/*/scripts/*.js', 'modules/*/scripts/*.js'],
			base: ['scripts/*.js'],
			templates: ['templates/*/scripts/*.js'],
			modules: ['modules/*/scripts/*.js']
		},
		watch:
		{
			scripts:
			{
				files: '<config:lint.all>',
				tasks: 'lint'
			}
		},
		csslint:
		{
			base:
			{
				src: 'styles/*.css',
				rules: grunt.cssRules
			},
			templates:
			{
				src: 'templates/*/styles/*.css',
				rules: grunt.cssRules
			},
			modules:
			{
				src: 'modules/*/styles/*.css',
				rules: grunt.cssRules
			}
		},
		jshint:
		{
			options:
			{
				boss: true,
				browser: true,
				curly: true,
				eqeqeq: true,
				eqnull: true,
				es5: true,
				immed: false,
				latedef: true,
				newcap: true,
				noarg: true,
				node: true,
				sub: true,
				undef: true
			},
			globals:
			{
				_gaq: true,
				_gat: true,
				jQuery: true,
				l: true,
				r: true
			}
		},
		smushit:
		{
			path:
			{
				src: 'templates/*/images'
			}
		}
	});

	/* load tasks */

	grunt.loadNpmTasks('grunt-css');
	grunt.loadNpmTasks('grunt-smushit');

	/* register tasks */

	grunt.registerTask('default', 'lint');
};