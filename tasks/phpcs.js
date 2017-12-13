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
				'includes/**/**/*.php',
				'assets/scripts/*.js',
				'assets/styles/*.css'
			]
		},
		modules:
		{
			src:
			[
				'modules/*/assets/scripts/*.js',
				'modules/*/assets/styles/*.css',
				'modules/**/**/*.php'
			]
		},
		templates:
		{
			src:
			[
				'templates/*/assets/scripts/*.js',
				'templates/*/assets/styles/*.css'
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