module.exports = function(grunt)
{
	/* define css rules */

	grunt.cssRules =
	{
		'adjoining-classes': false
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
			}
		},
		jshint:
		{
			options:
			{
				browser: true,
				curly: true,
				eqeqeq: true,
				immed: false,
				latedef: true,
				newcap: true,
				noarg: true,
				sub: true,
				undef: true,
				boss: true,
				eqnull: true,
				node: true,
				es5: true
			},
			globals:
			{
				_gat: true,
				$: true,
				jQuery: true,
				l: true,
				r: true
			}
		},
		htmllint:
		{
			all: ['templates/*/*.phtml']
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
	grunt.loadNpmTasks('grunt-html');
	grunt.loadNpmTasks('grunt-smushit');

	/* register tasks */

	grunt.registerTask('default', 'lint');
};