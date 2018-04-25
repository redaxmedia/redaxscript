module.exports = grunt =>
{
	'use strict';

	const config =
	{
		phpstanRoot:
		{
			command: 'vendor/bin/phpstan analyse --configuration=phpstan.neon --level 4 --no-progress *.php'
		},
		phpstanBase:
		{
			command: 'vendor/bin/phpstan analyse --configuration=phpstan.neon --level 0 --no-progress includes'
		},
		phpstanModules:
		{
			command: 'vendor/bin/phpstan analyse --configuration=phpstan.neon --level 1 --no-progress modules'
		},
		phpcpdRoot:
		{
			command: 'vendor/bin/phpcpd *.php'
		},
		phpcpdBase:
		{
			command: 'vendor/bin/phpcpd includes',
			options:
			{
				failOnError: false
			}
		},
		phpcpdModules:
		{
			command: 'vendor/bin/phpcpd modules',
			options:
			{
				failOnError: false
			}
		},
		phpunit:
		{
			command: 'vendor/bin/phpunit ' + grunt.option.flags()
		},
		phpunitParallel:
		{
			command: 'vendor/bin/paratest --processes=10 ' + grunt.option.flags()
		},
		phpServer:
		{
			command: 'php -S 127.0.0.1:8000'
		},
		openBrowser:
		{
			command: 'opn http://localhost:8000'
		},
		removeBuild:
		{
			command: 'rm -rf build'
		},
		options:
		{
			stdout: true,
			failOnError: true
		}
	};

	return config;
};