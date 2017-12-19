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
				'watch',
				'openbrowser'
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
