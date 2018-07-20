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
		moduleFormValidator:
		{
			src:
			[
				'modules/FormValidator/assets/scripts/form-validator.js'
			],
			dest: 'modules/FormValidator/dist/scripts/form-validator.min.js'
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
