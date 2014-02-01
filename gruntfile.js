module.exports = function (grunt)
{
	'use strict';

	/* config grunt */

	grunt.initConfig(
	{
		jshint:
		{
			dependency:
			[
				'gruntfile.js'
			],
			base:
			[
				'scripts/*.js'
			],
			modules:
			[
				'modules/*/scripts/*.js'
			],
			templates:
			[
				'templates/*/scripts/*.js'
			],
			options:
			{
				jshintrc: '.jshintrc'
			}
		},
		jsonlint:
		{
			dependency:
			[
				'composer.json',
				'package.json'
			],
			modules:
			[
				'modules/web_app/files/manifest.json'
			],
			provider:
			[
				'tests/provider/*.json'
			]
		},
		csslint:
		{
			base:
			{
				src:
				[
					'styles/*.css',
					'!styles/webkit.css'
				]
			},
			modules:
			{
				src:
				[
					'modules/*/styles/*.css',
					'!modules/gallery/styles/query.css'
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
				dir: 'config.php index.php install.php'
			},
			base:
			{
				dir: 'includes scripts styles'
			},
			languages:
			{
				dir: 'languages'
			},
			modules:
			{
				dir: 'modules'
			},
			templates:
			{
				dir: 'templates'
			},
			tests:
			{
				dir: 'tests'
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
			testsuite:
			{
				dir: ''
			},
			options:
			{
				bin: 'vendor/bin/phpunit'
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
				eol: 'crlf'
			}
		},
		shell:
		{
			tocBase:
			{
				command: 'php vendor/redaxmedia/tocgen/cli.php scripts .tocgen && php vendor/redaxmedia/tocgen/cli.php styles .tocgen'
			},
			tocModules:
			{
				command: 'php vendor/redaxmedia/tocgen/cli.php modules .tocgen'
			},
			tocTemplates:
			{
				command: 'php vendor/redaxmedia/tocgen/cli.php templates .tocgen'
			},
			tocLintBase:
			{
				command: 'php vendor/redaxmedia/tocgen/cli.php scripts .tocgen -l && php vendor/redaxmedia/tocgen/cli.php styles .tocgen -l'
			},
			tocLintModules:
			{
				command: 'php vendor/redaxmedia/tocgen/cli.php modules .tocgen -l'
			},
			tocLintTemplates:
			{
				command: 'php vendor/redaxmedia/tocgen/cli.php templates .tocgen -l'
			},
			apigenBase:
			{
				command: 'php vendor/apigen/apigen/apigen.php --source includes --destination ../redaxscript-api/base'
			},
			apigenTests:
			{
				command: 'php vendor/apigen/apigen/apigen.php --source tests --destination ../redaxscript-api/tests'
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
				files:
				[
					{
						src:
						[
							'ruleset.xml'
						],
						dest: 'vendor/squizlabs/php_codesniffer/CodeSniffer/Standards/Redaxmedia/'
					}
				]
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
					'modules/*/images/*.png',
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
		}
	});

	/* load tasks */

	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-csslint');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-qunit');
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
		'phpunit'
	]);
	grunt.registerTask('phplint',
	[
		'copy:ruleset',
		'phpcs'
	]);
	grunt.registerTask('toclint',
	[
		'shell:tocLintBase',
		'shell:tocLintModules',
		'shell:tocLintTemplates'
	]);
	grunt.registerTask('toc',
	[
		'shell:tocBase',
		'shell:tocModules',
		'shell:tocTemplates'
	]);
	grunt.registerTask('apigen',
	[
		'shell:apigenBase',
		'shell:apigenTests'
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
		'toc',
		'eol',
		'img',
		'smushit',
		'svgmin'
	]);
};