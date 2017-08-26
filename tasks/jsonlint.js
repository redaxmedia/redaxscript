module.exports = function ()
{
	'use strict';

	var config =
	{
		dependency:
		{
			src:
			[
				'composer.json',
				'package.json'
			]
		},
		ruleset:
		{
			src:
			[
				'.htmlhintrc',
				'.jscsrc',
				'.jshintrc',
				'.stylelintrc'
			]
		},
		languages:
		{
			src:
			[
				'languages/*.json'
			]
		},
		modules:
		{
			src:
			[
				'modules/**/*.json'
			]
		},
		provider:
		{
			src:
			[
				'tests/provider/*.json'
			]
		}
	};

	return config;
};