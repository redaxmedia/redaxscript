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
		'fallback-colors': false,
		'text-indent': false,
		'unique-headings': false,
		'outline-none': false,
		'qualified-headings': false
	};

	/* config grunt */

	grunt.initConfig(
	{
		lint:
		{
			base: ['scripts/*.js'],
			modules: ['modules/*/scripts/*.js'],
			templates: ['templates/*/scripts/*.js']
		},
		watch:
		{
			scripts:
			{
				files: ['<config:lint.base>', '<config:lint.modules>', '<config:lint.templates>'],
				tasks: 'lint'
			},
			styles:
			{
				files: ['<config:csslint.base.src>', '<config:csslint.modules.src>', '<config:csslint.templates.src>'],
				tasks: 'csslint'
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
		csslint:
		{
			base:
			{
				src: ['styles/*.css'],
				rules: grunt.cssRules
			},
			modules:
			{
				src: ['modules/*/styles/*.css'],
				rules: grunt.cssRules
			},
			templates:
			{
				src: ['templates/*/styles/*.css'],
				rules: grunt.cssRules
			}
		},
		bom:
		{
			base:
			{
				src: ['*.php', 'includes/*.php', 'includes/admin/*.php', 'styles/*.css', 'scripts/*.js']
			},
			languages:
			{
				src: ['languages/*.php']
			},
			modules:
			{
				src: ['modules/*/*.php', 'modules/*/styles/*.css', 'modules/*/scripts/*.js']
			},
			templates:
			{
				src: ['templates/*/*.phtml', 'templates/*/styles/*.css', 'templates/*/scripts/*.js']
			}
		},
		img:
		{
			modules:
			{
				src: ['modules/*/images/*']
			},
			templates:
			{
				src: ['templates/*/images/*']
			}
		},
		smushit:
		{
			modules:
			{
				src: ['<config:img.modules.src>']
			},
			templates:
			{
				src: ['<config:img.templates.src>']
			}
		}
	});

	/* load tasks */

	grunt.loadNpmTasks('grunt-css');
	grunt.loadNpmTasks('grunt-bom');
	grunt.loadNpmTasks('grunt-img');
	grunt.loadNpmTasks('grunt-smushit');

	/* register tasks */

	grunt.registerTask('default', 'lint');
	grunt.registerTask('optimize', 'bom img smushit');
};