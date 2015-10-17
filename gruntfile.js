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
					'scripts/*.js',
					'styles/*.css'
				]
			},
			modules:
			{
				src:
				[
					'modules/*/scripts/*.js',
					'modules/*/styles/*.css',
					'modules/*/*.php'
				]
			},
			templates:
			{
				src:
				[
					'templates/*/scripts/*.js',
					'templates/*/styles/*.css'
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
				standard: 'ruleset.xml',
				callback: function (error, stdout, stderr)
				{
					grunt.option('force', stderr.indexOf('PHP Parse error') > -1);
				}
			}
		},
		qunit:
		{
			development:
			{
				options:
				{
					urls:
					[
						'http://develop.redaxscript.com/qunit'
					]
				}
			}
		},
		phpunit:
		{
			development:
			{
				dir: 'tests/includes'
			},
			options:
			{
				bin: 'vendor/bin/phpunit',
				bootstrap: './tests/bootstrap.php',
				coverageClover: grunt.option('xml') ? grunt.option('xml') : false,
				coverageHtml: grunt.option('html') ? grunt.option('html') : false
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
					'scripts/**',
					'styles/**',
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
					'scripts/**',
					'styles/**',
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

			},
			distModulesAce:
			{
				src:
				[
					'modules/Ace/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/ace.zip'
				}
			},
			distModulesAnalytics:
			{
				src:
				[
					'modules/Analytics/**'
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
					'modules/Archive/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/archive.zip'
				}
			},
			distModulesCallHome:
			{
				src:
				[
					'modules/CallHome/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/call_home.zip'
				}
			},
			distModulesContact:
			{
				src:
				[
					'modules/Contact/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/contact.zip'
				}
			},
			distModulesDawanda:
			{
				src:
				[
					'modules/Dawanda/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/dawanda.zip'
				}
			},
			distModulesDirectoryLister:
			{
				src:
				[
					'modules/DirectoryLister/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/directory_lister.zip'
				}
			},
			distModulesDisqus:
			{
				src:
				[
					'modules/Disqus/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/disqus.zip'
				}
			},
			distModulesEditor:
			{
				src:
				[
					'modules/Editor/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/editor.zip'
				}
			},
			distModulesFeedGenerator:
			{
				src:
				[
					'modules/FeedGenerator/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/feed_generator.zip'
				}
			},
			distModulesFeedReader:
			{
				src:
				[
					'modules/FeedReader/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/feed_reader.zip'
				}
			},
			distModulesFileManager:
			{
				src:
				[
					'modules/file_manager/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/file_manager.zip'
				}
			},
			distModulesGallery:
			{
				src:
				[
					'modules/gallery/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/gallery.zip'
				}
			},
			distModulesGetFile:
			{
				src:
				[
					'modules/GetFile/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/get_file.zip'
				}
			},
			distModulesLazyLoad:
			{
				src:
				[
					'modules/LazyLoad/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/lazy_load.zip'
				}
			},
			distModulesLiveReload:
			{
				src:
				[
					'modules/LiveReload/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/live_reload.zip'
				}
			},
			distModulesMaps:
			{
				src:
				[
					'modules/Maps/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/maps.zip'
				}
			},
			distModulesPreview:
			{
				src:
				[
					'modules/Preview/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/preview.zip'
				}
			},
			distModulesQunit:
			{
				src:
				[
					'modules/Qunit/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/qunit.zip'
				}
			},
			distModulesRecentView:
			{
				src:
				[
					'modules/RecentView/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/recent_view.zip'
				}
			},
			distModulesShareThis:
			{
				src:
				[
					'modules/ShareThis/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/share_this.zip'
				}
			},
			distModulesSitemap:
			{
				src:
				[
					'modules/Sitemap/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/sitemap.zip'
				}
			},
			distModulesSitemapXml:
			{
				src:
				[
					'modules/SitemapXml/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/sitemap_xml.zip'
				}
			},
			distModulesSyntaxHighlighter:
			{
				src:
				[
					'modules/SyntaxHighlighter/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/syntax_highlighter.zip'
				}
			},
			distModulesValidator:
			{
				src:
				[
					'modules/Validator/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/validator.zip'
				}
			},
			distModulesWebApp:
			{
				src:
				[
					'modules/WebApp/**'
				],
				options:
				{
					archive: '../redaxscript-dist/files/modules/web_app.zip'
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
				},
				dot: true
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
				},
				dot: true
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
				},
				dot: true
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
				},
				dot: true
			}
		},
		jsonmin:
		{
			dependency:
			{
				src:
				[
					'composer.lock'
				],
				dest: 'composer.lock'
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
	grunt.loadNpmTasks('grunt-jscs');
	grunt.loadNpmTasks('grunt-jsonlint');
	grunt.loadNpmTasks('grunt-jsonmin');
	grunt.loadNpmTasks('grunt-phpcs');
	grunt.loadNpmTasks('grunt-phpunit');
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
		'jsonmin',
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
