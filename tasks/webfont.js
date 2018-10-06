module.exports = grunt =>
{
	'use strict';

	const config =
	{
		templateAdmin:
		{
			src:
			[
				'node_modules/mdi-svg/svg/account.svg',
				'node_modules/mdi-svg/svg/alert-circle-outline.svg',
				'node_modules/mdi-svg/svg/bell.svg',
				'node_modules/mdi-svg/svg/book-open-variant.svg',
				'node_modules/mdi-svg/svg/check-circle-outline.svg',
				'node_modules/mdi-svg/svg/chevron-down.svg',
				'node_modules/mdi-svg/svg/chevron-right.svg',
				'node_modules/mdi-svg/svg/close-circle-outline.svg',
				'node_modules/mdi-svg/svg/delete.svg',
				'node_modules/mdi-svg/svg/eye-off.svg',
				'node_modules/mdi-svg/svg/help-circle-outline.svg',
				'node_modules/mdi-svg/svg/lock.svg',
				'node_modules/mdi-svg/svg/minus.svg',
				'node_modules/mdi-svg/svg/pencil.svg',
				'node_modules/mdi-svg/svg/plus.svg',
				'node_modules/mdi-svg/svg/power.svg',
				'node_modules/mdi-svg/svg/settings.svg',
				'node_modules/mdi-svg/svg/subdirectory-arrow-right.svg'
			],
			dest: 'templates/admin/dist/fonts',
			options:
			{
				destCss: 'templates/admin/assets/styles',
				template: 'templates/admin/assets/styles/_template.css'
			}
		},
		templateDefault:
		{
			src:
			[
				'node_modules/mdi-svg/svg/account.svg',
				'node_modules/mdi-svg/svg/alert-circle-outline.svg',
				'node_modules/mdi-svg/svg/check-circle-outline.svg',
				'node_modules/mdi-svg/svg/chevron-left.svg',
				'node_modules/mdi-svg/svg/chevron-right.svg',
				'node_modules/mdi-svg/svg/close-circle-outline.svg',
				'node_modules/mdi-svg/svg/help-circle-outline.svg',
				'node_modules/mdi-svg/svg/magnify.svg',
				'node_modules/mdi-svg/svg/minus.svg',
				'node_modules/mdi-svg/svg/page-first.svg',
				'node_modules/mdi-svg/svg/page-last.svg',
				'node_modules/mdi-svg/svg/plus.svg'
			],
			dest: 'templates/default/dist/fonts',
			options:
			{
				destCss: 'templates/default/assets/styles',
				template: 'templates/default/assets/styles/_template.css'
			}
		},
		moduleDirectoryLister:
		{
			src:
			[
				'node_modules/mdi-svg/svg/file.svg',
				'node_modules/mdi-svg/svg/folder.svg',
				'node_modules/mdi-svg/svg/folder-open.svg'
			],
			dest: 'modules/DirectoryLister/dist/fonts',
			options:
			{
				destCss: 'modules/DirectoryLister/assets/styles',
				template: 'modules/DirectoryLister/assets/styles/_template.css'
			}
		},
		moduleSocialSharer:
		{
			src:
			[
				'node_modules/icomoon-free-npm/SVG/396-google-plus.svg',
				'node_modules/icomoon-free-npm/SVG/401-facebook.svg',
				'node_modules/icomoon-free-npm/SVG/406-telegram.svg',
				'node_modules/icomoon-free-npm/SVG/407-twitter.svg',
				'node_modules/icomoon-free-npm/SVG/404-whatsapp.svg'
			],
			dest: 'modules/SocialSharer/dist/fonts',
			options:
			{
				destCss: 'modules/SocialSharer/assets/styles',
				template: 'modules/SocialSharer/assets/styles/_template.css'
			}
		},
		moduleTableSorter:
		{
			src:
			[
				'node_modules/mdi-svg/svg/drag-vertical.svg'
			],
			dest: 'modules/TableSorter/dist/fonts',
			options:
			{
				destCss: 'modules/TableSorter/assets/styles',
				template: 'modules/TableSorter/assets/styles/_template.css'
			}
		},
		options:
		{
			font: 'icon',
			types:
			[
				'woff2'
			],
			autoHint: false,
			htmlDemo: false
		}
	};
	if (grunt.option('W') || grunt.option('webfont-compat'))
	{
		config.options.engine = 'node';
		config.options.normalize = true;
	}

	return config;
};
