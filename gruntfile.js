module.exports = function (grunt)
{
	'use strict';

	/* config grunt */

	grunt.initConfig(
	{
		version: grunt.file.readJSON('package.json').version,
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
					'modules/*/scripts/*.js'
				]
			},
			templates:
			{
				src:
				[
					'templates/*/scripts/*.js'
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
					'styles/*.css'
				]
			},
			modules:
			{
				src:
				[
					'modules/*/styles/*.css'
				]
			},
			templates:
			{
				src:
				[
					'templates/*/styles/*.css'
				]
			},
			options:
			{
				csslintrc: '.csslintrc'
			}
		},
		htmlhint:
		{
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
				dir:
				[
					'config.php',
					'index.php',
					'install.php'
				]
			},
			base:
			{
				dir:
				[
					'includes',
					'scripts',
					'styles'
				]
			},
			languages:
			{
				dir:
				[
					'languages'
				]
			},
			modules:
			{
				dir:
				[
					'modules'
				]
			},
			templates:
			{
				dir:
				[
					'templates'
				]
			},
			tests:
			{
				dir:
				[
					'tests'
				]
			},
			options:
			{
				bin: 'vendor/bin/phpcs',
				standard: 'Redaxmedia'
			}
		},
		qunit:
		{
			jquery:
			{
				options:
				{
					urls:
					[
						'http://develop.redaxscript.com/qunit.default'
					]
				}
			},
			zepto:
			{
				options:
				{
					urls:
					[
						'http://develop.redaxscript.com/qunit.zepto'
					]
				}
			}
		},
		phpunit:
		{
			development:
			{
				options:
				{
					debug: true
				}
			},
			integration:
			{
				options:
				{
					coverageClover: 'clover.xml'
				}
			},
			options:
			{
				bin: 'vendor/bin/phpunit',
				strict: true
			}
		},
		autoprefixer:
		{
			base:
			{
				src:
				[
					'styles/*.css'
				]
			},
			modules:
			{
				src:
				[
					'modules/*/styles/*.css'
				]
			},
			templates:
			{
				src:
				[
					'templates/*/styles/*.css'
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
		lineending:
		{
			css:
			{
				src:
				[
					'styles/*.css',
					'modules/*/styles/*.css',
					'templates/*/styles/*.css'
				],
				expand: true
			},
			js:
			{
				src:
				[
					'scripts/*.js',
					'modules/*/scripts/*.js',
					'templates/*/scripts/*.js'
				],
				expand: true
			},
			phtml:
			{
				src:
				[
					'modules/**/*.phtml',
					'templates/**/*.phtml'
				],
				expand: true
			},
			php:
			{
				src:
				[
					'*.php',
					'includes/**/*.php',
					'languages/*.php',
					'modules/**/*.php',
					'tests/**/*.php'
				],
				expand: true
			},
			options:
			{
				eol: 'lf'
			}
		},
		shell:
		{
			tocBase:
			{
				command: 'sh vendor/bin/tocgen.sh scripts .tocgen && sh vendor/bin/tocgen.sh styles .tocgen'
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
				command: 'sh vendor/bin/tocgen.sh scripts .tocgen -l && sh vendor/bin/tocgen.sh styles .tocgen -l'
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
				command: 'php vendor/apigen/apigen/apigen.php --template-config vendor/redaxmedia/redaxscript-apigen-template/config.neon --source config.php --source includes --destination ../redaxscript-api/base'
			},
			apiTests:
			{
				command: 'php vendor/apigen/apigen/apigen.php --template-config vendor/redaxmedia/redaxscript-apigen-template/config.neon --source tests --destination ../redaxscript-api/tests'
			},
			addUpstream:
			{
				command: 'git remote add upstream git://github.com/redaxmedia/redaxscript.git'
			},
			pullUpstream:
			{
				command: 'git pull upstream master'
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
			ruleset:
			{
				src:
				[
					'ruleset.xml'
				],
				dest: 'vendor/squizlabs/php_codesniffer/CodeSniffer/Standards/Redaxmedia/',
				expand: true
			},
			distFull:
			{
				src:
				[
					'<%=compress.distFull.src%>'
				],
				dest: '../redaxscript-dist/export/redaxscript_<%= version %>_full',
				expand: true
			},
			distLite:
			{
				src:
				[
					'<%=compress.distLite.src%>'
				],
				dest: '../redaxscript-dist/export/redaxscript_<%= version %>_lite',
				expand: true
			}
		},
		compress:
		{
			distFull:
			{
				src:
				[
					'includes/**',
					'languages/**',
					'modules/**',
					'templates/**',
					'config.php',
					'index.php',
					'install.php',
					'README.md'
				],
				options:
				{
					archive: '../redaxscript-dist/files/releases/redaxscript_<%= version %>_full.zip'
				}
			},
			distLite:
			{
				src:
				[
					'includes/**',
					'languages/en.php',
					'languages/misc.php',
					'modules/call_home/**',
					'templates/admin/**',
					'templates/default/**',
					'templates/install/**',
					'config.php',
					'index.php',
					'install.php',
					'README.md'
				],
				options:
				{
					archive: '../redaxscript-dist/files/releases/redaxscript_<%= version %>_lite.zip'
				}
			},
			distLanguages:
			{
				src:
				[
					'languages/*.php',
					'!languages/misc.php'
				],
				dest: '../redaxscript-dist/files',
				expand: true
			},
			distModulesAnalytics:
			{
				src:
				[
					'modules/analytics/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/analytics.zip'
				}
			},
			distModulesArchive:
			{
				src:
				[
					'modules/archive/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/archive.zip'
				}
			},
			distTemplatesCandy:
			{
				src:
				[
					'templates/candy/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/templates/candy.zip'
				}
			},
			distTemplatesScratch:
			{
				src:
				[
					'templates/scratch/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/templates/scratch.zip'
				}
			},
			distTemplatesTwitter:
			{
				src:
				[
					'templates/twitter/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/templates/twitter.zip'
				}
			},
			distTemplatesWide:
			{
				src:
				[
					'templates/wide/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/templates/wide.zip'
				}
			},
			distTemplatesZepto:
			{
				src:
				[
					'templates/zepto/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/templates/zepto.zip'
				}
			},
			distSQL:
			{
				src:
				[
					'../redaxscript-sql/<%= version %>/*.sql'
				],
				dest: '../redaxscript-dist/files/sql',
				expand: true
			},
			distMediaLogos:
			{
				src:
				[
					'../redaxscript-media/logos/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/media/logos.zip'
				}
			},
			distMediaScreenshots:
			{
				src:
				[
					'../redaxscript-media/screenshots/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/media/screenshots.zip'
				}
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
					'includes/*.php',
					'tests/*/*.php',
					'tests/*/*.json'
				],
				tasks:
				[
					'phpunit:development'
				]
			}
		}
	});

	/* load tasks */

	grunt.loadNpmTasks('grunt-autoprefixer');
	grunt.loadNpmTasks('grunt-contrib-compress');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-csslint');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-qunit');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-htmlhint');
	grunt.loadNpmTasks('grunt-img');
	grunt.loadNpmTasks('grunt-jsonlint');
	grunt.loadNpmTasks('grunt-lineending');
	grunt.loadNpmTasks('grunt-phpcs');
	grunt.loadNpmTasks('grunt-phpunit');
	grunt.loadNpmTasks('grunt-shell');
	grunt.loadNpmTasks('grunt-smushit');
	grunt.loadNpmTasks('grunt-svgmin');

	/* register tasks */

	grunt.registerTask('default',
	[
		'jshint',
		'jsonlint',
		'csslint',
		'htmlhint',
		'phplint',
		'toclint',
		'phpunit:integration'
	]);
	grunt.registerTask('phplint',
	[
		'copy:ruleset',
		'phpcs'
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
		'shell:apiTests'
	]);
	grunt.registerTask('eol',
	[
		'lineending'
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
		'eol',
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
