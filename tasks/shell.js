module.exports = grunt =>
{
	const run = command => process.platform === 'win32' ? 'powershell ' + command : command;
	const config =
	{
		testUnit:
		{
			command: run('vendor/bin/phpunit')
		},
		testUnitParallel:
		{
			command: run('vendor/bin/paratest')
		},
		testAcceptance:
		{
			command: run(
			[
				'wait-on http://localhost:8000',
				process.env.CYPRESS_PROJECT_ID && process.env.CYPRESS_RECORD_KEY ? 'cypress run --record' : 'cypress run'
			]
			.join('&&'))
		},
		testAcceptanceParallel:
		{
			command: run(
			[
				'wait-on http://localhost:8000',
				process.env.CYPRESS_PROJECT_ID && process.env.CYPRESS_RECORD_KEY ? 'cypress run --record --parallel' : 'cypress run'
			]
			.join('&&'))
		},
		startServer:
		{
			command: run(
			[
				'cross-env',
				grunt.option('N') || grunt.option('no-cache') ? 'NO_CACHE=true' : '',
				grunt.option('D') || grunt.option('debug-mode') ? 'DEBUG_MODE=true' : '',
				'php -S localhost:8000'
			]
			.join(' '))
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
