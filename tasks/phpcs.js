module.exports = grunt =>
{
	'use strict';

	const config =
	{
		root:
		{
			src:
			[
				'*.php'
			]
		},
		base:
		{
			src:
			[
				'includes/**/**/*.php'
			]
		},
		modules:
		{
			src:
			[
				'modules/**/**/*.php'
			]
		},
		tests:
		{
			src:
			[
				'tests/**/**/*.php'
			]
		},
		options:
		{
			bin: grunt.option('fix') ? 'vendor/bin/phpcbf' : 'vendor/bin/phpcs',
			standard: 'phpcs.xml'
		}
	};

	return config;
};