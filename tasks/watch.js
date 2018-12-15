module.exports = () =>
{
	'use strict';

	const config =
	{
		fonts:
		{
			files:
			[
				'templates/**/assets/styles/**/*.tpl',
				'modules/**/assets/styles/**/*.tpl'
			],
			tasks:
			[
				'build-fonts'
			]
		},
		styles:
		{
			files:
			[
				'assets/styles/**/*.css',
				'templates/**/assets/styles/**/*.css',
				'modules/**/assets/styles/**/*.css'
			],
			tasks:
			[
				'build-styles'
			]
		},
		scripts:
		{
			files:
			[
				'assets/scripts/**/*.js',
				'templates/**/assets/scripts/**/*.js',
				'modules/**/assets/scripts/**/*.js'
			],
			tasks:
			[
				'build-scripts'
			]
		},
		general:
		{
			files:
			[
				'includes/**/*.phtml',
				'modules/**/*.phtml',
				'includes/**/*.php',
				'modules/**/*.php'
			]
		},
		options:
		{
			livereload: 7000,
			spawn: false
		}
	};

	return config;
};
