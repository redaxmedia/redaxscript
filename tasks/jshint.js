module.exports = function ()
{
	'use strict';

	var config =
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
			jshintrc: '.jshintrc'
		}
	};

	return config;
};