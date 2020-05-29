module.exports = () =>
{
	'use strict';

	const config =
	{
		templateConsole:
		{
			src:
			[
				'templates/console/assets/scripts/behavior.js'
			],
			dest: 'templates/console/dist/scripts/console.min.js'
		},
		templateInstall:
		{
			src:
			[
				'templates/install/assets/scripts/behavior.js'
			],
			dest: 'templates/install/dist/scripts/install.min.js'
		},
		moduleAliasGenerator:
		{
			src:
			[
				'modules/AliasGenerator/assets/scripts/alias-generator.js'
			],
			dest: 'modules/AliasGenerator/dist/scripts/alias-generator.min.js'
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
		moduleCodeEditor:
		{
			src:
			[
				'modules/CodeEditor/assets/scripts/code-editor.js'
			],
			dest: 'modules/CodeEditor/dist/scripts/code-editor.min.js'
		},
		moduleDialog:
		{
			src:
			[
				'modules/Dialog/assets/scripts/dialog.js'
			],
			dest: 'modules/Dialog/dist/scripts/dialog.min.js'
		},
		moduleFormValidator:
		{
			src:
			[
				'modules/FormValidator/assets/scripts/form-validator.js'
			],
			dest: 'modules/FormValidator/dist/scripts/form-validator.min.js'
		},
		moduleGallery:
		{
			src:
			[
				'modules/Gallery/assets/scripts/gallery.js'
			],
			dest: 'modules/Gallery/dist/scripts/gallery.min.js'
		},
		moduleMaps:
		{
			src:
			[
				'modules/Maps/assets/scripts/maps.js'
			],
			dest: 'modules/Maps/dist/scripts/maps.min.js'
		},
		moduleRankSorter:
		{
			src:
			[
				'modules/RankSorter/assets/scripts/rank-sorter.js'
			],
			dest: 'modules/RankSorter/dist/scripts/rank-sorter.min.js'
		},
		moduleSyntaxHighlighter:
		{
			src:
			[
				'modules/SyntaxHighlighter/assets/scripts/syntax-highlighter.js'
			],
			dest: 'modules/SyntaxHighlighter/dist/scripts/syntax-highlighter.min.js'
		},
		moduleTextareaResizer:
		{
			src:
			[
				'modules/TextareaResizer/assets/scripts/textarea-resizer.js'
			],
			dest: 'modules/TextareaResizer/dist/scripts/textarea-resizer.min.js'
		},
		moduleUnmaskPassword:
		{
			src:
			[
				'modules/UnmaskPassword/assets/scripts/unmask-password.js'
			],
			dest: 'modules/UnmaskPassword/dist/scripts/unmask-password.min.js'
		},
		moduleVisualEditor:
		{
			src:
			[
				'modules/VisualEditor/assets/scripts/visual-editor.js'
			],
			dest: 'modules/VisualEditor/dist/scripts/visual-editor.min.js'
		},
		options:
		{
			presets:
			[
				'@babel/preset-env',
				'minify'
			]
		}
	};

	return config;
};
