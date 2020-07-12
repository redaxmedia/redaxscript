module.exports = () =>
{
	const config =
	{
		base:
		{
			src:
			[
				'assets/**/*.css'
			]
		},
		templates:
		{
			src:
			[
				'templates/**/assets/**/*.css'
			]
		},
		modules:
		{
			src:
			[
				'modules/**/assets/**/*.css'
			]
		}
	};

	return config;
};
