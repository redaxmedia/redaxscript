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
		moduleAce:
		{
			src:
			[
				'modules/Ace/assets/scripts/ace.js'
			],
			dest: 'modules/Ace/dist/scripts/ace.min.js'
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
		moduleExperiments:
		{
			src:
			[
				'modules/Experiments/assets/scripts/experiments.js'
			],
			dest: 'modules/Experiments/dist/scripts/experiments.min.js'
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
		moduleSyntaxHighlighter:
		{
			src:
			[
				'modules/SyntaxHighlighter/assets/scripts/syntax-highlighter.js'
			],
			dest: 'modules/SyntaxHighlighter/dist/scripts/syntax-highlighter.min.js'
		},
		moduleTableSorter:
		{
			src:
			[
				'modules/TableSorter/assets/scripts/table-sorter.js'
			],
			dest: 'modules/TableSorter/dist/scripts/table-sorter.min.js'
		},
		moduleTextareaResizer:
		{
			src:
			[
				'modules/TextareaResizer/assets/scripts/textarea-resizer.js'
			],
			dest: 'modules/TextareaResizer/dist/scripts/textarea-resizer.min.js'
		},
		moduleTinymce:
		{
			src:
			[
				'modules/Tinymce/assets/scripts/tinymce.js'
			],
			dest: 'modules/Tinymce/dist/scripts/tinymce.min.js'
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
