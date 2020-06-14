module.exports = grunt =>
{
	'use strict';

	const config =
	{
		templateAdmin:
		{
			src:
			[
				'node_modules/@mdi/svg/svg/account.svg',
				'node_modules/@mdi/svg/svg/alert-circle-outline.svg',
				'node_modules/@mdi/svg/svg/bell.svg',
				'node_modules/@mdi/svg/svg/book-open-variant.svg',
				'node_modules/@mdi/svg/svg/check.svg',
				'node_modules/@mdi/svg/svg/check-circle-outline.svg',
				'node_modules/@mdi/svg/svg/chevron-down.svg',
				'node_modules/@mdi/svg/svg/chevron-right.svg',
				'node_modules/@mdi/svg/svg/close.svg',
				'node_modules/@mdi/svg/svg/close-circle-outline.svg',
				'node_modules/@mdi/svg/svg/cog.svg',
				'node_modules/@mdi/svg/svg/cube-outline.svg',
				'node_modules/@mdi/svg/svg/delete.svg',
				'node_modules/@mdi/svg/svg/eye-off.svg',
				'node_modules/@mdi/svg/svg/help-circle-outline.svg',
				'node_modules/@mdi/svg/svg/lock.svg',
				'node_modules/@mdi/svg/svg/minus.svg',
				'node_modules/@mdi/svg/svg/pencil.svg',
				'node_modules/@mdi/svg/svg/plus.svg',
				'node_modules/@mdi/svg/svg/power.svg',
				'node_modules/@mdi/svg/svg/subdirectory-arrow-right.svg'
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
				'node_modules/@mdi/svg/svg/alert-circle-outline.svg',
				'node_modules/@mdi/svg/svg/check-circle-outline.svg',
				'node_modules/@mdi/svg/svg/chevron-left.svg',
				'node_modules/@mdi/svg/svg/chevron-right.svg',
				'node_modules/@mdi/svg/svg/close-circle-outline.svg',
				'node_modules/@mdi/svg/svg/help-circle-outline.svg',
				'node_modules/@mdi/svg/svg/magnify.svg',
				'node_modules/@mdi/svg/svg/minus.svg',
				'node_modules/@mdi/svg/svg/page-first.svg',
				'node_modules/@mdi/svg/svg/page-last.svg',
				'node_modules/@mdi/svg/svg/plus.svg'
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
				'node_modules/@mdi/svg/svg/file.svg',
				'node_modules/@mdi/svg/svg/folder.svg',
				'node_modules/@mdi/svg/svg/folder-open.svg'
			],
			dest: 'modules/DirectoryLister/dist/fonts',
			options:
			{
				destCss: 'modules/DirectoryLister/assets/styles',
				template: 'modules/DirectoryLister/assets/styles/_template.css'
			}
		},
		moduleRankSorter:
		{
			src:
			[
				'node_modules/@mdi/svg/svg/drag-vertical.svg'
			],
			dest: 'modules/RankSorter/dist/fonts',
			options:
			{
				destCss: 'modules/RankSorter/assets/styles',
				template: 'modules/RankSorter/assets/styles/_template.css'
			}
		},
		moduleSocialSharer:
		{
			src:
			[
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
		moduleVisualEditor:
		{
			src:
			[
				'node_modules/@mdi/svg/svg/format-bold.svg',
				'node_modules/@mdi/svg/svg/format-italic.svg',
				'node_modules/@mdi/svg/svg/format-underline.svg',
				'node_modules/@mdi/svg/svg/format-strikethrough.svg',
				'node_modules/@mdi/svg/svg/format-paragraph.svg',
				'node_modules/@mdi/svg/svg/format-header-1.svg',
				'node_modules/@mdi/svg/svg/format-header-2.svg',
				'node_modules/@mdi/svg/svg/format-header-3.svg',
				'node_modules/@mdi/svg/svg/format-list-numbered.svg',
				'node_modules/@mdi/svg/svg/format-list-bulleted.svg',
				'node_modules/@mdi/svg/svg/format-clear.svg',
				'node_modules/@mdi/svg/svg/format-pilcrow.svg',
				'node_modules/@mdi/svg/svg/link-variant.svg',
				'node_modules/@mdi/svg/svg/image-outline.svg',
				'node_modules/@mdi/svg/svg/upload.svg',
				'node_modules/@mdi/svg/svg/undo.svg',
				'node_modules/@mdi/svg/svg/redo.svg'
			],
			dest: 'modules/VisualEditor/dist/fonts',
			options:
			{
				destCss: 'modules/VisualEditor/assets/styles',
				template: 'modules/VisualEditor/assets/styles/_template.css'
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
