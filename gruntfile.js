module.exports = function (grunt)
{
	'use strict';

	/* polyfill */

	require('babel-polyfill');

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
					'.htmlhintrc',
					'.jscsrc',
					'.jshintrc',
					'.stylelintrc',
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
					'includes/**/*.php',
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
					'modules/**/*.php'
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
			benchs:
			{
				src:
				[
					'benchs/**/*.php'
				]
			},
			tests:
			{
				src:
				[
					'tests/**/*.php'
				]
			},
			options:
			{
				bin: grunt.option('fix') ? 'vendor/bin/phpcbf' : 'vendor/bin/phpcs',
				standard: 'phpcs.xml'
			}
		},
		concat:
		{
			base:
			{
				src:
				[
					'assets/styles/_query.css',
					'assets/styles/normalize.css',
					'assets/styles/helper.css',
					'assets/styles/animate.css',
					'assets/styles/accordion.css',
					'assets/styles/box.css',
					'assets/styles/dialog.css',
					'assets/styles/dropdown.css',
					'assets/styles/form.css',
					'assets/styles/grid.css',
					'assets/styles/media.css',
					'assets/styles/navigation.css',
					'assets/styles/tab.css',
					'assets/styles/table.css',
					'assets/styles/tooltip.css',
					'assets/styles/layout.css'
				],
				dest: 'dist/styles/base.min.css'
			},
			templateAdmin:
			{
				src:
				[
					'assets/styles/_query.css',
					'templates/admin/assets/styles/_variable.css',
					'templates/admin/assets/styles/typo.css',
					'templates/admin/assets/styles/layout.css',
					'templates/admin/assets/styles/box.css',
					'templates/admin/assets/styles/button.css',
					'templates/admin/assets/styles/dashboard.css',
					'templates/admin/assets/styles/dialog.css',
					'templates/admin/assets/styles/dock.css',
					'templates/admin/assets/styles/field.css',
					'templates/admin/assets/styles/form.css',
					'templates/admin/assets/styles/grid.css',
					'templates/admin/assets/styles/icon.css',
					'templates/admin/assets/styles/interface.css',
					'templates/admin/assets/styles/list.css',
					'templates/admin/assets/styles/panel.css',
					'templates/admin/assets/styles/tab.css',
					'templates/admin/assets/styles/table.css',
					'templates/admin/assets/styles/query.css',
					'templates/admin/assets/styles/note.css'
				],
				dest: 'templates/admin/dist/styles/admin.min.css'
			},
			templateConsole:
			{
				src:
				[
					'templates/console/assets/styles/_variable.css',
					'assets/styles/normalize.css',
					'templates/console/assets/styles/console.css'
				],
				dest: 'templates/console/dist/styles/console.min.css'
			},
			templateDefault:
			{
				src:
				[
					'assets/styles/_query.css',
					'templates/default/assets/styles/_variable.css',
					'templates/default/assets/styles/typo.css',
					'templates/default/assets/styles/layout.css',
					'templates/default/assets/styles/animate.css',
					'templates/default/assets/styles/box.css',
					'templates/default/assets/styles/button.css',
					'templates/default/assets/styles/dialog.css',
					'templates/default/assets/styles/field.css',
					'templates/default/assets/styles/form.css',
					'templates/default/assets/styles/grid.css',
					'templates/default/assets/styles/icon.css',
					'templates/default/assets/styles/list.css',
					'templates/default/assets/styles/media.css',
					'templates/default/assets/styles/navigation.css',
					'templates/default/assets/styles/table.css',
					'templates/default/assets/styles/teaser.css',
					'templates/default/assets/styles/tooltip.css',
					'templates/default/assets/styles/note.css'
				],
				dest: 'templates/default/dist/styles/default.min.css'
			},
			templateInstall:
			{
				src:
				[
					'assets/styles/_query.css',
					'templates/default/assets/styles/_variable.css',
					'templates/install/assets/styles/layout.css'
				],
				dest: 'templates/install/dist/styles/install.min.css'
			}
		},
		postcss:
		{
			base:
			{
				src:
				[
					'dist/styles/*.min.css'
				],
				options:
				{
					processors:
					[
						require('postcss-custom-properties'),
						require('postcss-custom-media'),
						require('postcss-custom-selectors'),
						require('postcss-extend'),
						require('postcss-nesting'),
						require('postcss-color-gray'),
						require('postcss-color-function'),
						require('autoprefixer')(
						{
							browsers: 'last 2 versions',
							cascade: false
						})
					]
				}
			},
			templates:
			{
				src:
				[
					'templates/*/dist/styles/*.min.css'
				],
				options:
				{
					processors:
					[
						require('postcss-custom-properties'),
						require('postcss-custom-media'),
						require('postcss-custom-selectors'),
						require('postcss-extend'),
						require('postcss-nesting'),
						require('postcss-color-gray'),
						require('postcss-color-function'),
						require('autoprefixer')(
						{
							browsers: 'last 2 versions',
							cascade: false
						})
					]
				}
			},
			stylelintBase:
			{
				src:
				[
					'assets/styles/*.css'
				],
				options:
				{
					processors:
					[
						require('stylelint'),
						require('postcss-reporter')(
						{
							throwError: true
						})
					]
				}
			},
			stylelintTemplate:
			{
				src:
				[
					'templates/*/assets/styles/*.css'
				],
				options:
				{
					processors:
					[
						require('stylelint'),
						require('postcss-reporter')(
						{
							throwError: true
						})
					]
				}
			}
		},
		shell:
		{
			phpbench:
			{
				command: 'php vendor/bin/phpbench run benchs/phpbench --bootstrap=benchs/phpbench/bootstrap.php --progress=dots'
			},
			phpunit:
			{
				command: 'php vendor/bin/phpunit --configuration=phpunit.xml ' + grunt.option.flags()
			},
			phpunitParallel:
			{
				command: 'php vendor/bin/paratest --processes=10 --configuration=phpunit.xml ' + grunt.option.flags()
			},
			phpcpdBase:
			{
				command: 'php vendor/bin/phpcpd includes',
				options:
				{
					failOnError: false
				}
			},
			phpcpdModules:
			{
				command: 'php vendor/bin/phpcpd modules',
				options:
				{
					failOnError: false
				}
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
				command: 'sh vendor/bin/tocgen.sh templates/admin/assets .tocgen -l && sh vendor/bin/tocgen.sh templates/default/assets .tocgen -l'
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
				dest: '../redaxscript-export/redaxscript_<%= version %>_full',
				dot: true,
				expand: true
			},
			distLite:
			{
				src:
				[
					'<%=compress.distLite.src%>'
				],
				dest: '../redaxscript-export/redaxscript_<%= version %>_lite',
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
					'dist/**',
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
					archive: '../redaxscript-files/releases/redaxscript_<%= version %>_full.zip'
				},
				dot: true
			},
			distLite:
			{
				src:
				[
					'database/**',
					'dist/**',
					'includes/**',
					'languages/en.json',
					'libraries/**',
					'modules/CallHome/**',
					'modules/Validator/**',
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
					archive: '../redaxscript-files/releases/redaxscript_<%= version %>_lite.zip'
				},
				dot: true
			}
		},
		'ftp-deploy':
		{
			develop:
			{
				src:
				[
					'../redaxscript-files'
				],
				dest: 'files',
				auth:
				{
					host: 'develop.redaxscript.com',
					port: 21,
					authKey: 'develop',
					authPath: '../credentials/.redaxscript'
				},
				forceVerbose: true
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
			build:
			{
				files:
				[
					'assets/styles/*.css',
					'templates/**/assets/styles/*.css'
				],
				tasks:
				[
					'build'
				]
			},
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

	/* dynamic compress */

	grunt.dynamicCompress = function (path)
	{
		var target = grunt.file.expand(path),
			targetArray;

		for (var i in target)
		{
			targetArray = target[i].split('.');
			grunt.config.set('compress.' + targetArray[0],
			{
				src:
				[
					targetArray[1] ? target[i] : target[i] + '/**'
				],
				options:
				{
					archive: '../redaxscript-files/' + targetArray[0] + '.zip'
				},
				dot: true
			});
		}
	};
	grunt.dynamicCompress('languages/*.json');
	grunt.dynamicCompress('modules/*');
	grunt.dynamicCompress('templates/*');

	/* load tasks */

	grunt.loadNpmTasks('grunt-contrib-compress');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-ftp-deploy');
	grunt.loadNpmTasks('grunt-htmlhint');
	grunt.loadNpmTasks('grunt-img');
	grunt.loadNpmTasks('grunt-jscs');
	grunt.loadNpmTasks('grunt-jsonlint');
	grunt.loadNpmTasks('grunt-phpcs');
	grunt.loadNpmTasks('grunt-postcss');
	grunt.loadNpmTasks('grunt-shell');
	grunt.loadNpmTasks('grunt-svgmin');

	/* register tasks */

	grunt.registerTask('default',
	[
		'jscs',
		'jshint',
		'jsonlint',
		'stylelint',
		'htmlhint',
		'phpcs',
		'phpcpd',
		'toclint'
	]);
	grunt.registerTask('stylelint',
	[
		'postcss:stylelintBase',
		'postcss:stylelintTemplate'
	]);
	grunt.registerTask('toclint',
	[
		'shell:toclintBase',
		'shell:toclintModules',
		'shell:toclintTemplates'
	]);
	grunt.registerTask('test',
	[
		'phpunit'
	]);
	grunt.registerTask('phpbench',
	[
		'shell:phpbench'
	]);
	grunt.registerTask('phpunit',
	[
		'shell:phpunit'
	]);
	grunt.registerTask('phpunitParallel',
	[
		'shell:phpunitParallel'
	]);
	grunt.registerTask('phpcpd',
	[
		'shell:phpcpdBase',
		'shell:phpcpdModules'
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
		'toc',
		'img',
		'svgmin'
	]);
	grunt.registerTask('build',
	[
		'concat',
		'postcss:base',
		'postcss:templates'
	]);
	grunt.registerTask('dist',
	[
		'copy:distFull',
		'copy:distLite',
		'compress'
	]);
	grunt.registerTask('deploy',
	[
		'ftp-deploy'
	]);
};
