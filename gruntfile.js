module.exports = function (grunt)
{
	'use strict';

	/* polyfill */

	require('es6-promise').polyfill();

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
					'dist/styles/*.min.css'
				]
			},
			modules:
			{
				src:
				[
					'modules/*/dist/styles/*.min.css'
				]
			},
			templates:
			{
				src:
				[
					'templates/*/dist/styles/*.min.css'
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
		concat:
		{
			base:
			{
				src:
				[
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
					'templates/admin/assets/styles/typo.css',
					'templates/admin/assets/styles/layout.css',
					'templates/admin/assets/styles/box.css',
					'templates/admin/assets/styles/button.css',
					'templates/admin/assets/styles/dialog.css',
					'templates/admin/assets/styles/dock.css',
					'templates/admin/assets/styles/field.css',
					'templates/admin/assets/styles/form.css',
					'templates/admin/assets/styles/grid.css',
					'templates/admin/assets/styles/icon.css',
					'templates/admin/assets/styles/interface.css',
					'templates/admin/assets/styles/list.css',
					'templates/admin/assets/styles/tab.css',
					'templates/admin/assets/styles/table.css',
					'templates/admin/assets/styles/query.css',
					'templates/admin/assets/styles/note.css'
				],
				dest: 'templates/admin/dist/styles/admin.min.css'
			},
			templateDefault:
			{
				src:
				[
					'templates/default/assets/styles/_query.css',
					'templates/default/assets/styles/_variable.css',
					'templates/default/assets/styles/typo.css',
					'templates/default/assets/styles/layout.css',
					'templates/default/assets/styles/animate.css',
					'templates/default/assets/styles/box.css',
					'templates/default/assets/styles/button.css',
					'templates/default/assets/styles/dialog.css',
					'templates/default/assets/styles/field.css',
					'templates/default/assets/styles/form.css',
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
			}
		},
		postcss:
		{
			base:
			{
				src:
				[
					'dist/styles/*.min.css'
				]
			},
			templates:
			{
				src:
				[
					'templates/*/dist/styles/*.min.css'
				]
			},
			options:
			{
				processors:
				[
					require('postcss-color-function'),
					require('postcss-color-gray'),
					require('postcss-css-variables'),
					require('postcss-custom-media'),
					require('postcss-extend'),
					require('postcss-nested'),
					require('autoprefixer')(
					{
						browsers: 'last 2 versions',
						cascade: false
					})
				]
			}
		},
		shell:
		{
			phpbench:
			{
				command: 'php vendor/bin/phpbench run benchs --bootstrap=benchs/bootstrap.php --progress=dots'
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
					archive: '../redaxscript-files/releases/redaxscript_<%= version %>_lite.zip'
				},
				dot: true
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
					'templates/**/assets/styles/typo.css'
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

	/* dynamic dist */

	grunt.dynamicDist = function (path)
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
					target[i] + (targetArray[1] ? '' : '/**')
				],
				options:
				{
					archive: '../redaxscript-files/' + targetArray[0].toLowerCase() + '.zip'
				},
				dot: true
			});
		}
	};
	grunt.dynamicDist('languages/*.json');
	grunt.dynamicDist('modules/*');
	grunt.dynamicDist('templates/*');

	/* load tasks */

	grunt.loadNpmTasks('grunt-contrib-compress');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-csslint');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-watch');
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
		'toc',
		'img',
		'svgmin'
	]);
	grunt.registerTask('build',
	[
		'concat',
		'postcss'
	]);
	grunt.registerTask('dist',
	[
		'copy:distFull',
		'copy:distLite',
		'compress'
	]);
};
