module.exports = function (grunt)
{
	'use strict';

	/* config grunt */

	grunt.initConfig(
	{
		watch:
		{
			scripts:
			{
				files: ['<%=jshint.base%>', '<%=jshint.modules%>', '<%=jshint.templates%>'],
				tasks: ['jshint']
			},
			styles:
			{
				files: ['<%=csslint.base.src%>', '<%=csslint.modules.src%>', '<%=csslint.templates.src%>'],
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
					urls: ['http://develop.redaxscript.com/qunit']
				}
			}
		},
		csslint:
		{
			base:
			{
				src: ['styles/*.css', '!styles/webkit.css']
			},
			modules:
			{
				src: ['modules/*/styles/*.css']
			},
			templates:
			{
				src: ['templates/*/styles/*.css']
			},
			options:
			{
				csslintrc: '.csslintrc'
			}
		},
		shell:
		{
			tocBase:
			{
				command: 'php ../tocgen/tocgen.php scripts && php ../tocgen/tocgen.php styles'
			},
			tocModules:
			{
				command: 'php ../tocgen/tocgen.php modules -r'
			},
			tocTemplates:
			{
				command: 'php ../tocgen/tocgen.php templates -r'
			},
			svgoAdmin:
			{
				command: 'svgo --disable removeViewBox -f templates/admin/images'
			},
			svgoDefault:
			{
				command: 'svgo --disable removeViewBox -f templates/default/images'
			},
			options:
			{
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
				src: ['<%=img.modules.src%>']
			},
			templates:
			{
				src: ['<%=img.templates.src%>']
			}
		}
	});

	/* load tasks */

	grunt.loadNpmTasks('grunt-contrib-csslint');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-qunit');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-shell');
	grunt.loadNpmTasks('grunt-img');
	grunt.loadNpmTasks('grunt-smushit');

	/* register tasks */

	grunt.registerTask('default', ['jshint']);
	grunt.registerTask('toc', ['shell:tocBase', 'shell:tocModules', 'shell:tocTemplates']);
	grunt.registerTask('svgo', ['shell:svgoAdmin', 'shell:svgoDefault']);
	grunt.registerTask('optimize', ['toc', 'img', 'smushit', 'svgo']);
};