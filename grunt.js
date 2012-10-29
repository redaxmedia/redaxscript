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
		'text-indent': false,
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
				tasks: 'lint qunit'
			},
			styles:
			{
				files: ['<config:csslint.base.src>', '<config:csslint.modules.src>', '<config:csslint.templates.src>'],
				tasks: 'csslint'
			}
		},
		qunit:
		{
			src: ['http://develop.redaxscript.com/qunit']
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
				latedef: true,
				newcap: true,
				noarg: true,
				noempty: true,
				node: true,
				strict: true,
				sub: true,
				trailing: true,
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
				src: ['*.php', 'includes/**/*.php', 'scripts/*.js', 'styles/*.css']
			},
			languages:
			{
				src: ['languages/*.php']
			},
			modules:
			{
				src: ['modules/**/*.php', 'modules/**/*.css', 'modules/**/*.js']
			},
			templates:
			{
				src: ['templates/**/*.phtml', 'templates/**/*.css', 'templates/**/*.js']
			}
		},
		shell:
		{
			tocBase:
			{
				command: 'php ../tocgen/tocgen.php scripts && php ../tocgen/tocgen.php styles',
				stdout: true
			},
			tocModules:
			{
				command: 'php ../tocgen/tocgen.php modules',
				stdout: true
			},
			tocTemplates:
			{
				command: 'php ../tocgen/tocgen.php templates',
				stdout: true
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
	grunt.loadNpmTasks('grunt-shell');
	grunt.loadNpmTasks('grunt-img');
	grunt.loadNpmTasks('grunt-smushit');

	/* register tasks */

	grunt.registerTask('default', 'lint');
	grunt.registerTask('toc', 'shell:tocBase shell:tocModules shell:tocTemplates');
	grunt.registerTask('optimize', 'bom img smushit');
};