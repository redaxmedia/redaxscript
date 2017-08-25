module.exports = function ()
{
	'use strict';

	var config =
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
	};

	return config;
};