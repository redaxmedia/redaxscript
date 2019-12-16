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
			command: run('vendor/bin/phpcpd modules')
		},
		phpstanRoot:
		{
			command: run('vendor/bin/phpstan analyse console.php index.php install.php --configuration=phpstan.neon --level 5 --no-progress')
		},
		phpstanBase:
		{
			command: run('vendor/bin/phpstan analyse includes --configuration=phpstan.neon --level 1 --no-progress')
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
		testUnit:
		{
			command: run('vendor/bin/phpunit')
		},
		testUnitParallel:
		{
			command: run('vendor/bin/paratest --processes=10')
		},
		testUnitMutation:
		{
			command: run('vendor/bin/infection --threads=10 --only-covered')
		},
		testAcceptance:
		{
			command: run('vendor/bin/phpunit --configuration=phpunit.acceptance.xml')
		},
		testAcceptanceParallel:
		{
			command: run('vendor/bin/paratest --processes=10 --configuration=phpunit.acceptance.xml')
		},
		startHub:
		{
			command: run('docker run --net=host selenium/standalone-chrome-debug:3.141.59-europium')
		},
		stopHub:
		{
			command: run('kill-port 4444')
		},
		startServer:
		{
			command: grunt.option('D') || grunt.option('debug-mode') ? run('DEBUG=true php -S 0.0.0.0:8000') : run('php -S 0.0.0.0:8000')
		},
		stopServer:
		{
			command: run('kill-port 8000')
		},
		stopWatch:
		{
			command: run('kill-port 7000')
		},
		installLiveReload:
		{
			command: run('php console.php install module --alias=LiveReload')
		},
		uninstallLiveReload:
		{
			command: run('php console.php uninstall module --alias=LiveReload')
		},
		openBrowser:
		{
			command: run('open-cli http://localhost:8000')
		},
		createBuild:
		{
			command: run('make-dir build')
		},
		removeBuild:
		{
			command: run('del build')
		},
		options:
		{
			stdout: true,
			failOnError: true
		}
	};

	return config;
};
