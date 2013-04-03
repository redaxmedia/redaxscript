module.exports = function (grunt)
{
	'use strict';

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
		watch:
		{
			scripts:
			{
				files: ['<config:lint.base>', '<config:lint.modules>', '<config:lint.templates>'],
				tasks: ['lint, qunit']
			},
			styles:
			{
				files: ['<config:csslint.base.src>', '<config:csslint.modules.src>', '<config:csslint.templates.src>'],
				tasks: ['csslint']
			}
		},
		jshint:
		{
			gruntfile: ['gruntfile.js'],
			base: ['scripts/*.js'],
			modules: ['modules/*/scripts/*.js'],
			templates: ['templates/*/scripts/*.js'],
			options:
			{
				jshintrc: '.jshintrc'
			}
		},
		qunit:
		{
			develop:
			{
				options:
				{
					timeout: 10000,
					urls: ['http://develop.redaxscript.com/qunit']
				}
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
		shell:
		{
			tocBase:
			{
				command: 'php ../tocgen/tocgen.php scripts && php ../tocgen/tocgen.php styles',
				stdout: true
			},
			tocModules:
			{
				command: 'php ../tocgen/tocgen.php modules -r',
				stdout: true
			},
			tocTemplates:
			{
				command: 'php ../tocgen/tocgen.php templates -r',
				stdout: true
			},
			svgoAdmin:
			{
				command: 'svgo --disable removeViewBox -f templates/admin/images',
				stdout: true
			},
			svgoDefault:
			{
				command: 'svgo --disable removeViewBox -f templates/default/images',
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

	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-qunit');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-css');
	grunt.loadNpmTasks('grunt-shell');
	grunt.loadNpmTasks('grunt-img');
	grunt.loadNpmTasks('grunt-smushit');

	/* register tasks */

	grunt.registerTask('default', ['jshint']);
	grunt.registerTask('toc', ['shell:tocBase', 'shell:tocModules', 'shell:tocTemplates']);
	grunt.registerTask('svgo', ['shell:svgoAdmin', 'shell:svgoDefault']);
	grunt.registerTask('optimize', ['bom', 'toc', 'img', 'smushit', 'svgo']);
};