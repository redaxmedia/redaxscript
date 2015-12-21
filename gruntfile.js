module.exports = function (grunt)
{
	'use strict';

	/* config grunt */

	grunt.initConfig(
	{
		version: grunt.file.readJSON('package.json').version,
		jscs:
		{
			dependency:
			{
				src:
				[
					'gruntfile.js'
				]
			},
			base:
			{
				src:
				[
					'assets/scripts/*.js'
				]
			},
			modules:
			{
				src:
				[
					'modules/*/assets/scripts/*.js'
				]
			},
			templates:
			{
				src:
				[
					'templates/*/assets/scripts/*.js'
				]
			},
			options:
			{
				config: '.jscsrc'
			}
		},
		jshint:
		{
			dependency:
			{
				src:
				[
					'gruntfile.js'
				]
			},
			base:
			{
				src:
				[
					'scripts/*.js'
				]
			},
			modules:
			{
				src:
				[
					'modules/*/assets/scripts/*.js'
				]
			},
			templates:
			{
				src:
				[
					'templates/*/assets/scripts/*.js'
				]
			},
			options:
			{
				jshintrc: '.jshintrc'
			}
		},
		jsonlint:
		{
			dependency:
			{
				src:
				[
					'composer.json',
					'package.json'
				]
			},
			ruleset:
			{
				src:
				[
					'.csslintrc',
					'.htmlhintrc',
					'.jscsrc',
					'.jshintrc',
					'.tocgen'
				]
			},
			languages:
			{
				src:
				[
					'languages/*.json'
				]
			},
			modules:
			{
				src:
				[
					'modules/**/*.json'
				]
			},
			provider:
			{
				src:
				[
					'tests/provider/*.json'
				]
			}
		},
		csslint:
		{
			base:
			{
				src:
				[
					'assets/styles/*.css'
				]
			},
			modules:
			{
				src:
				[
					'modules/*/assets/styles/*.css'
				]
			},
			templates:
			{
				src:
				[
					'templates/*/assets/styles/*.css'
				]
			},
			options:
			{
				csslintrc: '.csslintrc'
			}
		},
		htmlhint:
		{
			database:
			{
				src:
				[
					'database/**/*.phtml'
				]
			},
			modules:
			{
				src:
				[
					'modules/**/*.phtml'
				]
			},
			templates:
			{
				src:
				[
					'templates/**/*.phtml'
				]
			},
			options:
			{
				htmlhintrc: '.htmlhintrc'
			}
		},
		phpcs:
		{
			root:
			{
				src:
				[
					'index.php',
					'install.php'
				]
			},
			base:
			{
				src:
				[
					'includes/*/*.php',
					'includes/*.php',
					'assets/scripts/*.js',
					'assets/styles/*.css'
				]
			},
			modules:
			{
				src:
				[
					'modules/*/assets/scripts/*.js',
					'modules/*/assets/styles/*.css',
					'modules/*/*.php'
				]
			},
			templates:
			{
				src:
				[
					'templates/*/assets/scripts/*.js',
					'templates/*/assets/styles/*.css'
				]
			},
			tests:
			{
				src:
				[
					'tests/*/*.php',
					'tests/*.php'
				]
			},
			options:
			{
				bin: 'vendor/bin/phpcs',
				standard: 'ruleset.xml'
			}
		},
		autoprefixer:
		{
			base:
			{
				src:
				[
					'assets/styles/*.css'
				]
			},
			modules:
			{
				src:
				[
					'modules/*/assets/styles/*.css'
				]
			},
			templates:
			{
				src:
				[
					'templates/*/assets/styles/*.css'
				]
			},
			options:
			{
				browsers:
				[
					'last 2 Android versions',
					'last 2 iOS versions',
					'last 2 Chrome versions',
					'last 3 Explorer versions',
					'last 2 Firefox versions',
					'last 2 Opera versions',
					'last 2 Safari versions'
				]
			}
		},
		shell:
		{
			phpbench:
			{
				command: 'php vendor/bin/phpbench run benchs --bootstrap=benchs/bootstrap.php'
			},
			phpunit:
			{
				command: 'php vendor/bin/phpunit --configuration=phpunit.xml ' + grunt.option.flags()
			},
			tocBase:
			{
				command: 'sh vendor/bin/tocgen.sh assets .tocgen'
			},
			tocModules:
			{
				command: 'sh vendor/bin/tocgen.sh modules .tocgen'
			},
			tocTemplates:
			{
				command: 'sh vendor/bin/tocgen.sh templates .tocgen'
			},
			toclintBase:
			{
				command: 'sh vendor/bin/tocgen.sh assets .tocgen -l'
			},
			toclintModules:
			{
				command: 'sh vendor/bin/tocgen.sh modules .tocgen -l'
			},
			toclintTemplates:
			{
				command: 'sh vendor/bin/tocgen.sh templates .tocgen -l'
			},
			apiBase:
			{
				command: 'php vendor/bin/apigen generate --source includes --destination ../redaxscript-api/base'
			},
			apiModules:
			{
				command: 'php vendor/bin/apigen generate --source modules --destination ../redaxscript-api/modules'
			},
			apiTests:
			{
				command: 'php vendor/bin/apigen generate --source tests --destination ../redaxscript-api/tests'
			},
			addUpstream:
			{
				command: 'git remote add upstream git://github.com/redaxmedia/redaxscript.git'
			},
			pullUpstream:
			{
				command: 'git pull upstream master && git pull upstream develop'
			},
			removeUpstream:
			{
				command: 'git remote rm upstream'
			},
			options:
			{
				stdout: true,
				failOnError: true
			}
		},
		copy:
		{
			distFull:
			{
				src:
				[
					'<%=compress.distFull.src%>'
				],
				dest: '../redaxscript-dist/export/redaxscript_<%= version %>_full',
				dot: true,
				expand: true
			},
			distLite:
			{
				src:
				[
					'<%=compress.distLite.src%>'
				],
				dest: '../redaxscript-dist/export/redaxscript_<%= version %>_lite',
				dot: true,
				expand: true
			}
		},
		compress:
		{
			distFull:
			{
				src:
				[
					'database/**',
					'includes/**',
					'languages/**',
					'libraries/**',
					'modules/**',
					'assets/scripts/**',
					'assets/styles/**',
					'templates/**',
					'config.php',
					'index.php',
					'install.php',
					'README.md',
					'.htaccess'
				],
				options:
				{
					archive: '../redaxscript-dist/files/releases/redaxscript_<%= version %>_full.zip'
				},
				dot: true
			},
			distLite:
			{
				src:
				[
					'database/**',
					'includes/**',
					'languages/en.json',
					'libraries/**',
					'modules/CallHome/**',
					'assets/scripts/**',
					'assets/styles/**',
					'templates/admin/**',
					'templates/default/**',
					'templates/install/**',
					'config.php',
					'index.php',
					'install.php',
					'README.md',
					'.htaccess'
				],
				options:
				{
					archive: '../redaxscript-dist/files/releases/redaxscript_<%= version %>_lite.zip'
				},
				dot: true
			},
			distLanguages:
			{
				src:
				[
					'languages/*.json'
				],
				dest: '../redaxscript-dist/files',
				ext: '.zip',
				expand: true

			}
		},
		img:
		{
			modules:
			{
				src:
				[
					'modules/*/images/*.gif',
					'modules/*/images/*.jpg',
					'modules/*/images/*.png'
				]
			},
			templates:
			{
				src:
				[
					'templates/*/images/*.gif',
					'templates/*/images/*.jpg',
					'templates/*/images/*.png'
				]
			}
		},
		smushit:
		{
			modules:
			{
				src:
				[
					'<%=img.modules.src%>'
				]
			},
			templates:
			{
				src:
				[
					'<%=img.templates.src%>'
				]
			}
		},
		svgmin:
		{
			modules:
			{
				src:
				[
					'modules/*/images/*.svg'
				],
				expand: true
			},
			templates:
			{
				src:
				[
					'templates/*/images/*.svg'
				],
				expand: true
			},
			options:
			{
				plugins:
				[
					{
						removeViewBox: false
					}
				]
			}
		},
		watch:
		{
			phpunit:
			{
				files:
				[
					'includes/**/*.php',
					'tests/**/*.php',
					'tests/**/*.json'
				],
				tasks:
				[
					'phpunit'
				]
			}
		}
	});

	/* dynamic dist */

	grunt.dynamicDist = function (directory)
	{
		var target = grunt.file.expand(directory + '/*');

		for (var i in target)
		{
			grunt.config.set('compress.' + target[i],
			{
				src:
				[
					target[i] + '/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/' + target[i].toLowerCase() + '.zip'
				},
				dot: true
			});
		}
	};
	grunt.dynamicDist('modules');
	grunt.dynamicDist('templates');

	/* load tasks */

	grunt.loadNpmTasks('grunt-autoprefixer');
	grunt.loadNpmTasks('grunt-contrib-compress');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-csslint');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-htmlhint');
	grunt.loadNpmTasks('grunt-img');
	grunt.loadNpmTasks('grunt-jscs');
	grunt.loadNpmTasks('grunt-jsonlint');
	grunt.loadNpmTasks('grunt-phpcs');
	grunt.loadNpmTasks('grunt-shell');
	grunt.loadNpmTasks('grunt-smushit');
	grunt.loadNpmTasks('grunt-svgmin');

	/* register tasks */

	grunt.registerTask('default',
	[
		'jscs',
		'jshint',
		'jsonlint',
		'csslint',
		'htmlhint',
		'phpcs',
		'toclint'
	]);
	grunt.registerTask('phpbench',
	[
		'shell:phpbench'
	]);
	grunt.registerTask('phpunit',
	[
		'shell:phpunit'
	]);
	grunt.registerTask('toclint',
	[
		'shell:toclintBase',
		'shell:toclintModules',
		'shell:toclintTemplates'
	]);
	grunt.registerTask('toc',
	[
		'shell:tocBase',
		'shell:tocModules',
		'shell:tocTemplates'
	]);
	grunt.registerTask('api',
	[
		'shell:apiBase',
		'shell:apiModules',
		'shell:apiTests'
	]);
	grunt.registerTask('sync',
	[
		'shell:addUpstream',
		'shell:pullUpstream',
		'shell:removeUpstream'
	]);
	grunt.registerTask('optimize',
	[
		'autoprefixer',
		'toc',
		'img',
		'smushit',
		'svgmin'
	]);
	grunt.registerTask('dist',
	[
		'copy:distFull',
		'copy:distLite',
		'compress'
	]);
};
