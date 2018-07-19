module.exports = grunt =>
{
	'use strict';

	const run = command => process.platform === 'win32' ? 'powershell ' + command : command;
	const config =
	{
		phpcpdRoot:
		{
			command: run('vendor/bin/phpcpd console.php index.php install.php')
		},
		phpcpdBase:
		{
			command: run('vendor/bin/phpcpd includes'),
			options:
			{
				failOnError: false
			}
		},
		phpcpdModules:
		{
			command: run('vendor/bin/phpcpd modules'),
			options:
			{
				failOnError: false
			}
		},
		phpstanRoot:
		{
			command: run('vendor/bin/phpstan analyse console.php index.php install.php --configuration=phpstan.neon --level 4 --no-progress')
		},
		phpstanBase:
		{
			command: run('vendor/bin/phpstan analyse includes --configuration=phpstan.neon --level 0 --no-progress')
		},
		phpstanModules:
		{
			command: run('vendor/bin/phpstan analyse modules --configuration=phpstan.neon --level 1 --no-progress')
		},
		phpmdRoot:
		{
			command: run('vendor/bin/phpmd console.php,index.php,install.php text unusedcode')
		},
		phpmdBase:
		{
			command: run('vendor/bin/phpmd includes text unusedcode'),
			options:
			{
				failOnError: false
			}
		},
		phpmdModules:
		{
			command: run('vendor/bin/phpmd modules text unusedcode')
		},
		phpunit:
		{
			command: run('vendor/bin/phpunit ' + grunt.option.flags())
		},
		phpunitParallel:
		{
			command: run('vendor/bin/paratest --processes=10 ' + grunt.option.flags())
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
			command: 'del-cli build'
		},
		options:
		{
			stdout: true,
			failOnError: true
		}
	};

	return config;
};
