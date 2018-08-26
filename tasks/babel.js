module.exports = () =>
{
	'use strict';

	const config =
	{
		templateAdmin:
		{
			src:
			[
				'templates/admin/assets/scripts/alias.js'
			],
			dest: 'templates/admin/dist/scripts/admin.min.js'
		},
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
		moduleFormValidator:
		{
			src:
			[
				'modules/FormValidator/assets/scripts/form-validator.js'
			],
			dest: 'modules/FormValidator/dist/scripts/form-validator.min.js'
		},
		moduleTextareaResizer:
		{
			src:
			[
				'modules/TextareaResizer/assets/scripts/textarea-resizer.js'
			],
			dest: 'modules/TextareaResizer/dist/scripts/textarea-resizer.min.js'
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
