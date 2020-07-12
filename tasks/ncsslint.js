module.exports = () =>
{
	const config =
	{
		database:
		{
			options:
			{
				path: 'database/html/**/*.phtml'
			}
		},
		templateAdmin:
		{
			options:
			{
				path: 'templates/admin/*.phtml',
				namespace: 'rs-admin'
			}
		},
		templateConsole:
		{
			options:
			{
				path: 'templates/console/*.phtml'
			}
		},
		templateDefault:
		{
			options:
			{
				path: 'templates/default/*.phtml'
			}
		},
		templateInstall:
		{
			options:
			{
				path: 'templates/install/*.phtml'
			}
		},
		templateSkeleton:
		{
			options:
			{
				path: 'templates/skeleton/*.phtml'
			}
		}
	};

	return config;
};
