module.exports = function (grunt)
{
	'use strict';

	/* polyfill */

	require('babel-polyfill');
	grunt.path = require('path');

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
					'includes/**/**/*.php',
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
					'modules/**/**/*.php'
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
					'benchs/**/**/*.php'
				]
			},
			tests:
			{
				src:
				[
					'tests/**/**/*.php'
				]
			},
			options:
			{
				bin: grunt.option('fix') ? 'vendor/bin/phpcbf' : 'vendor/bin/phpcs',
				standard: 'phpcs.xml'
			}
		},
		diffJSON:
		{
			languages:
			{
				src:
				[
					'languages/*.json',
					'!languages/en.json'
				],
				dest: 'build/language.json'
			},
			options:
			{
				type: 'key',
				report:
				{
					obsolete: 'error',
					missing: 'error'
				}
			}
		},
		formatJSON:
		{
			languages:
			{
				src:
				[
					'languages/en.json'
				],
				dest: 'build/language.json',
				options:
				{
					remove:
					[
						'_package',
						'_index'
					]
				}
			}
		},
		concat:
		{
			base:
			{
				src:
				[
					'assets/styles/normalize.css',
					'assets/styles/animate.css'
				],
				dest: 'dist/styles/base.min.css'
			},
			templateAdmin:
			{
				src:
				[
					'assets/styles/_query.css',
					'assets/styles/_clearfix.css',
					'assets/styles/_dialog.css',
					'assets/styles/_redirect.css',
					'assets/styles/_table.css',
					'templates/admin/assets/styles/_icon.css',
					'templates/admin/assets/styles/_variable.css',
					'templates/admin/assets/styles/typo.css',
					'templates/admin/assets/styles/accordion.css',
					'templates/admin/assets/styles/button.css',
					'templates/admin/assets/styles/control.css',
					'templates/admin/assets/styles/dialog.css',
					'templates/admin/assets/styles/dock.css',
					'templates/admin/assets/styles/field.css',
					'templates/admin/assets/styles/form.css',
					'templates/admin/assets/styles/helper.css',
					'templates/admin/assets/styles/panel.css',
					'templates/admin/assets/styles/tab.css',
					'templates/admin/assets/styles/table.css',
					'templates/admin/assets/styles/note.css'
				],
				dest: 'templates/admin/dist/styles/admin.min.css'
			},
			templateConsole:
			{
				src:
				[
					'assets/styles/normalize.css',
					'templates/console/assets/styles/_variable.css',
					'templates/console/assets/styles/console.css'
				],
				dest: 'templates/console/dist/styles/console.min.css'
			},
			templateDefault:
			{
				src:
				[
					'assets/styles/_query.css',
					'assets/styles/_clearfix.css',
					'assets/styles/_dialog.css',
					'assets/styles/_dropdown.css',
					'assets/styles/_redirect.css',
					'assets/styles/_table.css',
					'templates/default/assets/styles/_icon.css',
					'templates/default/assets/styles/_variable.css',
					'templates/default/assets/styles/typo.css',
					'templates/default/assets/styles/accordion.css',
					'templates/default/assets/styles/breadcrumb.css',
					'templates/default/assets/styles/button.css',
					'templates/default/assets/styles/content.css',
					'templates/default/assets/styles/dialog.css',
					'templates/default/assets/styles/field.css',
					'templates/default/assets/styles/footer.css',
					'templates/default/assets/styles/form.css',
					'templates/default/assets/styles/header.css',
					'templates/default/assets/styles/helper.css',
					'templates/default/assets/styles/layout.css',
					'templates/default/assets/styles/list.css',
					'templates/default/assets/styles/media.css',
					'templates/default/assets/styles/navigation.css',
					'templates/default/assets/styles/pagination.css',
					'templates/default/assets/styles/result.css',
					'templates/default/assets/styles/sidebar.css',
					'templates/default/assets/styles/tab.css',
					'templates/default/assets/styles/table.css',
					'templates/default/assets/styles/teaser.css',
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
					'templates/install/assets/styles/install.css'
				],
				dest: 'templates/install/dist/styles/install.min.css'
			},
			modulePreview:
			{
				src:
				[
					'modules/Preview/assets/styles/preview.css'
				],
				dest: 'modules/Preview/dist/styles/preview.min.css'
			},
			moduleTinymceContent:
			{
				src:
				[
					'templates/default/assets/styles/_variable.css',
					'modules/Tinymce/assets/styles/content.css'
				],
				dest: 'modules/Tinymce/dist/styles/content.min.css'
			},
			moduleTinymceSkin:
			{
				src:
				[
					'modules/Tinymce/assets/styles/skin.css'
				],
				dest: 'modules/Tinymce/dist/styles/skin.min.css'
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
						require('postcss-nesting'),
						require('postcss-extend'),
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
					'templates/*/dist/styles/*.min.css',
					'modules/*/dist/styles/*.min.css'
				],
				options:
				{
					processors:
					[
						require('postcss-custom-properties'),
						require('postcss-custom-media'),
						require('postcss-custom-selectors'),
						require('postcss-nesting'),
						require('postcss-extend'),
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
			},
			colorguardTemplate:
			{
				src:
				[
					'templates/*/dist/styles/*.css'
				],
				options:
				{
					processors:
					[
						require('colorguard')(
						{
							threshold: 0.5,
							allowEquivalentNotation: true
						}),
						require('postcss-reporter')(
						{
							throwError: true
						})
					]
				}
			}
		},
		webfont:
		{
			templateAdmin:
			{
				src:
				[
					'node_modules/material-design-icons/action/svg/production/ic_delete_24px.svg',
					'node_modules/material-design-icons/action/svg/production/ic_done_24px.svg',
					'node_modules/material-design-icons/action/svg/production/ic_info_24px.svg',
					'node_modules/material-design-icons/action/svg/production/ic_lock_open_24px.svg',
					// 'node_modules/material-design-icons/action/svg/production/ic_lock_outline_24px.svg',
					'node_modules/material-design-icons/action/svg/production/ic_settings_24px.svg',
					// 'node_modules/material-design-icons/action/svg/production/ic_visibility_24px.svg',
					'node_modules/material-design-icons/action/svg/production/ic_visibility_off_24px.svg',
					'node_modules/material-design-icons/communication/svg/production/ic_chat_bubble_24px.svg',
					'node_modules/material-design-icons/communication/svg/production/ic_import_contacts_24px.svg',
					'node_modules/material-design-icons/communication/svg/production/ic_live_help_24px.svg',
					'node_modules/material-design-icons/communication/svg/production/ic_vpn_key_24px.svg',
					'node_modules/material-design-icons/content/svg/production/ic_add_24px.svg',
					'node_modules/material-design-icons/content/svg/production/ic_clear_24px.svg',
					'node_modules/material-design-icons/content/svg/production/ic_create_24px.svg',
					'node_modules/material-design-icons/content/svg/production/ic_remove_24px.svg',
					// 'node_modules/material-design-icons/editor/svg/production/ic_insert_drive_file_24px.svg',
					// 'node_modules/material-design-icons/folder/svg/production/ic_folder_24px.svg',
					// 'node_modules/material-design-icons/navigation/svg/production/ic_chevron_left_24px.svg',
					'node_modules/material-design-icons/navigation/svg/production/ic_chevron_right_24px.svg',
					'node_modules/material-design-icons/navigation/svg/production/ic_expand_less_24px.svg',
					'node_modules/material-design-icons/navigation/svg/production/ic_expand_more_24px.svg',
					'node_modules/material-design-icons/social/svg/production/ic_person_24px.svg'
				],
				dest: 'templates/admin/assets/fonts',
				options:
				{
					destCss: 'templates/admin/assets/styles',
					template: 'templates/admin/assets/styles/_icon.tpl'
				}
			},
			templateDefault:
			{
				src:
				[
					'node_modules/material-design-icons/action/svg/production/ic_search_24px.svg',
					'node_modules/material-design-icons/navigation/svg/production/ic_chevron_right_24px.svg'
					// 'node_modules/material-design-icons/action/svg/production/ic_done_24px.svg',
					// 'node_modules/material-design-icons/action/svg/production/ic_favorite_24px.svg',
					// 'node_modules/material-design-icons/action/svg/production/ic_info_24px.svg',
					// 'node_modules/material-design-icons/communication/svg/production/ic_chat_bubble_24px.svg',
					// 'node_modules/material-design-icons/communication/svg/production/ic_live_help_24px.svg',
					// 'node_modules/material-design-icons/content/svg/production/ic_add_24px.svg',
					// 'node_modules/material-design-icons/content/svg/production/ic_clear_24px.svg',
					// 'node_modules/material-design-icons/content/svg/production/ic_remove_24px.svg',
					// 'node_modules/material-design-icons/image/svg/production/ic_image_24px.svg',
					// 'node_modules/material-design-icons/navigation/svg/production/ic_chevron_left_24px.svg',
					// 'node_modules/material-design-icons/navigation/svg/production/ic_chevron_right_24px.svg',
					// 'node_modules/material-design-icons/navigation/svg/production/ic_expand_less_24px.svg',
					// 'node_modules/material-design-icons/navigation/svg/production/ic_expand_more_24px.svg',
					// 'node_modules/material-design-icons/navigation/svg/production/ic_menu_24px.svg'
				],
				dest: 'templates/default/assets/fonts',
				options:
				{
					destCss: 'templates/default/assets/styles',
					template: 'templates/default/assets/styles/_icon.tpl'
				}
			},
			options:
			{
				font: 'icon',
				types:
				[
					'woff',
					'woff2'
				],
				codepoints:
				{
					'search': 0x2315,
					'add': 0x2b,
					'chat-bubble': 0x25b6,
					// 'chevron-left': 0x3008,
					'chevron-right': 0x3009,
					'clear': 0xd7,
					'create': 0x270E,
					'delete': 0x2297,
					'done': 0x2714,
					'expand-less': 0x2227,
					'expand-more': 0x2228,
					// 'favorite': 0x2665,
					// 'folder': 0x26D8,
					'import-contacts': 0x25EB,
					// 'image': 0x2600,
					'info': 0x21,
					// 'insert-drive-file': 0x2752,
					'live-help': 0x2691,
					'lock-open': 0x2190,
					// 'lock-outline': 0x2192,
					// 'menu': 0x2261,
					'person': 0x26C4,
					'remove': 0x2d,
					'settings': 0x2731,
					// 'visibility': 0x2295,
					'visibility-off': 0x2298,
					'vpn-key': 0x2386
				},
				rename: function (name)
				{
					return grunt.path.basename(name).split('_').join('-').replace('ic-', '').replace('-24px', '');
				},
				autoHint: false,
				htmlDemo: false
			}
		},
		shell:
		{
			phpbench:
			{
				command: 'php vendor/bin/phpbench run benchs/phpbench --bootstrap=benchs/phpbench/includes/bootstrap.php --progress=dots'
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
			initZwamp:
			{
				command: 'mkdir -p ../redaxscript-zwamp'
			},
			downloadZwamp:
			{
				command: 'wget downloads.sourceforge.net/project/zwamp/zwamp-x64-2.2.1.zip -O ../redaxscript-zwamp/zwamp.zip'
			},
			downloadPapercutService:
			{
				command: 'wget github.com/Jaben/Papercut/releases/download/4.6.1.12/PapercutService.4.6.1.12.zip -O ../redaxscript-zwamp/papercut-service.zip'
			},
			batchZwamp:
			{
				command: 'echo "start zwamp.exe & start vdrive/.sys/papercut/Papercut.Service.exe" > ../redaxscript-zwamp/zwamp/start.bat'
			},
			zipZwamp:
			{
				command: 'cd ../redaxscript-zwamp && zip redaxscript-zwamp.zip zwamp -r && cd ../redaxscript'
			},
			unzipZwamp:
			{
				command: 'cd ../redaxscript-zwamp && unzip zwamp.zip -d zwamp -x *.txt && cd ../redaxscript'
			},
			unzipPapercutService:
			{
				command: 'cd ../redaxscript-zwamp && unzip papercut-service.zip -d zwamp/vdrive/.sys/papercut && cd ../redaxscript'
			},
			removeZwampWeb:
			{
				command: 'rm -rf ../redaxscript-zwamp/zwamp/vdrive/web'
			},
			removeZwampBuild:
			{
				command: 'rm -rf ../redaxscript-zwamp/zwamp'
			},
			removeBuild:
			{
				command: 'rm -rf build'
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
			},
			distZwamp:
			{
				src:
				[
					'<%=compress.distFull.src%>'
				],
				dest: '../redaxscript-zwamp/zwamp/vdrive/web',
				dot: true,
				expand: true
			}
		},
		rename:
		{
			templateAdmin:
			{
				src:
				[
					'templates/admin/assets/styles/icon.tpl'
				],
				dest: 'templates/admin/assets/styles/_icon.css'
			},
			templateDefault:
			{
				src:
				[
					'templates/default/assets/styles/icon.tpl'
				],
				dest: 'templates/default/assets/styles/_icon.css'
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
					archive: '../redaxscript-files/releases/redaxscript-<%= version %>-full.zip'
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
					archive: '../redaxscript-files/releases/redaxscript-<%= version %>-lite.zip'
				},
				dot: true
			}
		},
		deployFTP:
		{
			files:
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
				}
			},
			zwamp:
			{
				src:
				[
					'../redaxscript-zwamp'
				],
				dest: 'zwamp',
				exclusions:
				[
					'../redaxscript-zwamp/zwamp.zip'
				],
				auth:
				{
					host: 'develop.redaxscript.com',
					port: 21,
					authKey: 'develop',
					authPath: '../credentials/.redaxscript'
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
	grunt.loadNpmTasks('grunt-contrib-rename');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-diff-json');
	grunt.loadNpmTasks('grunt-ftp-deploy');
	grunt.loadNpmTasks('grunt-htmlhint');
	grunt.loadNpmTasks('grunt-img');
	grunt.loadNpmTasks('grunt-jscs');
	grunt.loadNpmTasks('grunt-json-format');	
	grunt.loadNpmTasks('grunt-jsonlint');
	grunt.loadNpmTasks('grunt-phpcs');
	grunt.loadNpmTasks('grunt-postcss');
	grunt.loadNpmTasks('grunt-shell');
	grunt.loadNpmTasks('grunt-svgmin');
	grunt.loadNpmTasks('grunt-webfont');

	/* rename tasks */

	grunt.task.renameTask('json-format', 'formatJSON');
	grunt.task.renameTask('ftp-deploy', 'deployFTP');
	
	/* register tasks */

	grunt.registerTask('default',
	[
		'jscs',
		'jshint',
		'jsonlint',
		'stylelint',
		'colorguard',
		'htmlhint',
		'phpcs',
		'phpcpd',
		'languagelint',
		'toclint'
	]);
	grunt.registerTask('stylelint',
	[
		'postcss:stylelintBase',
		'postcss:stylelintTemplate'
	]);
	grunt.registerTask('colorguard',
	[
		'postcss:colorguardTemplate'
	]);
	grunt.registerTask('languagelint',
	[
		'formatJSON:languages',
		'diffJSON:languages',
		'shell:removeBuild'
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
	grunt.registerTask('optimize',
	[
		'toc',
		'img',
		'svgmin'
	]);
	grunt.registerTask('build',
	[
		'build-font',
		'build-css'
	]);
	grunt.registerTask('build-font',
	[
		'webfont',
		'rename:templateAdmin',
		'rename:templateDefault'
	]);
	grunt.registerTask('build-css',
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
	grunt.registerTask('zwamp',
	[
		'shell:initZwamp',
		'shell:downloadZwamp',
		'shell:downloadPapercutService',
		'shell:unzipZwamp',
		'shell:unzipPapercutService',
		'shell:removeZwampWeb',
		'copy:distZwamp',
		'shell:batchZwamp',
		'shell:zipZwamp',
		'shell:removeZwampBuild'
	]);
	grunt.registerTask('deploy',
	[
		'deployFTP'
	]);
};
