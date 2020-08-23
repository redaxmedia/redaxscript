module.exports = () =>
{
	const config =
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
		languages:
		{
			src:
			[
				'languages/*.json'
			],
			options:
			{
				rules:
				{
					'i18n-json/identical-keys':
					[
						'error',
						{
							filePath: require('path').resolve('languages/en.json')
						}
					]
				}
			}
		},
		modules:
		{
			src:
			[
				'modules/*/assets/scripts/*.js',
				'!modules/Debugger/assets/scripts/debugger.js'
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
			config: '.eslintrc'
		}
	};

	return config;
};
