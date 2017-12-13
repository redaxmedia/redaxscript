module.exports = () =>
{
	'use strict';

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
				path: 'templates/console/*.phtml',
				namespace: 'rs-console'
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
				path: 'templates/install/*.phtml',
				namespace: 'rs-install'
			}
		},
		templateSkeleton:
		{
			options:
			{
				path: 'templates/skeleton/*.phtml'
			}
		},
		options:
		{
			namespace: 'rs',
			selector: '*:not([class*="<?"]):not([class*="?>"])',
			logLevel: 'info',
			haltOnError: true
		}
	};

	return config;
};