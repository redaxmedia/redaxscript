module.exports = () =>
{
	'use strict';

	const config =
	{
		dependency:
		{
			src:
			[
				'gruntfile.js'
			]
		},
		tasks:
		{
			src:
			[
				'tasks/*.js'
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
				'modules/*/assets/scripts/*.js',
				'!modules/Debugger/assets/scripts/debugger.js'
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
			config: '.eslintrc'
		}
	};

	return config;
};