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
	};

	return config;
};