module.exports = () =>
{
	'use strict';

	const config =
	{
		base:
		{
			src:
			[
				'assets/scripts/dialog.js',
				'assets/scripts/form.js',
				'assets/scripts/interface.js',
				'assets/scripts/misc.js'
			],
			dest: 'dist/scripts/base.min.js'
		},
		templateAdmin:
		{
			src:
			[
				'templates/admin/assets/scripts/alias.js',
				'templates/admin/assets/scripts/panel.js'
			],
			dest: 'templates/admin/dist/scripts/admin.min.js'
		},
		templateConsole:
		{
			src:
			[
				'templates/console/assets/scripts/console.js'
			],
			dest: 'templates/console/dist/scripts/console.min.js'
		},
		templateInstall:
		{
			src:
			[
				'templates/install/assets/scripts/install.js'
			],
			dest: 'templates/install/dist/scripts/install.min.js'
		},
		moduleAce:
		{
			src:
			[
				'modules/Ace/assets/scripts/ace.js'
			],
			dest: 'modules/Ace/dist/scripts/ace.min.js'
		},
		moduleAnalytics:
		{
			src:
			[
				'modules/Analytics/assets/scripts/analytics.js'
			],
			dest: 'modules/Analytics/dist/scripts/analytics.min.js'
		},
		moduleCallHome:
		{
			src:
			[
				'modules/CallHome/assets/scripts/call-home.js'
			],
			dest: 'modules/CallHome/dist/scripts/call-home.min.js'
		},
		moduleExperiments:
		{
			src:
			[
				'modules/Experiments/assets/scripts/experiments.js'
			],
			dest: 'modules/Experiments/dist/scripts/experiments.min.js'
		},
		moduleLightGallery:
		{
			src:
			[
				'modules/LightGallery/assets/scripts/light-gallery.js'
			],
			dest: 'modules/LightGallery/dist/scripts/light-gallery.min.js'
		},
		moduleMaps:
		{
			src:
			[
				'modules/Maps/assets/scripts/maps.js'
			],
			dest: 'modules/Maps/dist/scripts/maps.min.js'
		},
		moduleSocialSharer:
		{
			src:
			[
				'modules/SocialSharer/assets/scripts/social-sharer.js'
			],
			dest: 'modules/SocialSharer/dist/scripts/social-sharer.min.js'
		},
		moduleSyntaxHighlighter:
		{
			src:
			[
				'modules/SyntaxHighlighter/assets/scripts/syntax-highlighter.js'
			],
			dest: 'modules/SyntaxHighlighter/dist/scripts/syntax-highlighter.min.js'
		},
		moduleTinymce:
		{
			src:
			[
				'modules/Tinymce/assets/scripts/tinymce.js'
			],
			dest: 'modules/Tinymce/dist/scripts/tinymce.min.js'
		}
	};

	return config;
};