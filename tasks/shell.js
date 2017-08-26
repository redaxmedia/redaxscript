module.exports = function (grunt)
{
	'use strict';

	var config =
	{
		phpstanRoot:
		{
			command: 'vendor/bin/phpstan analyse --configuration=phpstan.neon --level 7 --no-progress *.php'
		},
		phpstanBase:
		{
			command: 'vendor/bin/phpstan analyse --configuration=phpstan.neon --level 7 --no-progress includes'
		},
		phpstanModules:
		{
			command: 'vendor/bin/phpstan analyse --configuration=phpstan.neon --level 7 --no-progress modules'
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
			command: 'vendor/bin/phpunit --configuration=phpunit.xml ' + grunt.option.flags()
		},
		phpunitParallel:
		{
			command: 'vendor/bin/paratest --processes=10 --configuration=phpunit.xml ' + grunt.option.flags()
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