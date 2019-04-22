module.exports = grunt =>
{
	'use strict';

	const config =
	{
		serve:
		{
			tasks:
			[
				'start-server',
				'watch'
			]
		},
		options:
		{
			grunt: true,
			stream: true
		}
	};

	if (grunt.option('O') || grunt.option('open-browser'))
	{
		config.serve.tasks.push('open-browser');
	}

	return config;
};
