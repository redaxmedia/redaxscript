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
		tocBase:
		{
			command: 'sh vendor/bin/tocgen.sh assets .tocgen'
		},
		tocModules:
		{
			command: 'sh vendor/bin/tocgen.sh modules .tocgen'
		},
		tocTemplates:
		{
			command: 'sh vendor/bin/tocgen.sh templates .tocgen'
		},
		toclintBase:
		{
			command: 'sh vendor/bin/tocgen.sh assets .tocgen -l'
		},
		toclintModules:
		{
			command: 'sh vendor/bin/tocgen.sh modules .tocgen -l'
		},
		toclintTemplates:
		{
			command: 'sh vendor/bin/tocgen.sh templates/admin/assets .tocgen -l && sh vendor/bin/tocgen.sh templates/default/assets .tocgen -l'
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