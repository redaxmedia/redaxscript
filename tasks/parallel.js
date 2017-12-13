module.exports = () =>
{
	'use strict';

	const config =
	{
		serve:
		{
			tasks:
			[
				'phpserver',
				'watch'
			]
		},
		options:
		{
			grunt: true,
			stream: true
		}
	};

	return config;
};
