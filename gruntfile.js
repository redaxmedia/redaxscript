module.exports = grunt =>
{
	'use strict';

	/* config grunt */

	grunt.initConfig(
	{
		eslint: require('./tasks/eslint')(grunt),
		jsonlint: require('./tasks/jsonlint')(grunt),
		ncsslint: require('./tasks/ncsslint')(grunt),
		htmlhint: require('./tasks/htmlhint')(grunt),
		phpcs: require('./tasks/phpcs')(grunt),
		postcss: require('./tasks/postcss')(grunt),
		babel: require('./tasks/babel')(grunt),
		tocgen: require('./tasks/tocgen')(grunt),
		shell: require('./tasks/shell')(grunt),
		svgmin: require('./tasks/svgmin')(grunt),
		parallel: require('./tasks/parallel')(grunt),
		watch: require('./tasks/watch')(grunt)
	});

	/* load tasks */

	require('load-grunt-tasks')(grunt);

	/* register tasks */

	grunt.registerTask('default',
	[
		'eslint',
		'jsonlint',
		'stylelint',
		'colorguard',
		'ncsslint',
		'htmlhint',
		'phpcpd',
		'phpmd',
		'phpcs'
	]);
	grunt.registerTask('stylelint',
	[
		'postcss:stylelint'
	]);
	grunt.registerTask('stylefmt',
	[
		'postcss:stylefmt'
	]);
	grunt.registerTask('colorguard',
	[
		'postcss:colorguard'
	]);
	grunt.registerTask('phpcpd',
	[
		'shell:phpcpdRoot',
		'shell:phpcpdBase',
		'shell:phpcpdModules'
	]);
	grunt.registerTask('phpmd',
	[
		'shell:phpmdRoot',
		'shell:phpmdBase',
		'shell:phpmdModules'
	]);
	grunt.registerTask('test-unit',
	[
		'shell:testUnit'
	]);
	grunt.registerTask('test-unit-parallel',
	[
		'shell:testUnitParallel'
	]);
	grunt.registerTask('test-unit-mutation',
	[
		'shell:testUnitMutation'
	]);
	grunt.registerTask('test-acceptance',
	[
		'shell:createBuild',
		'shell:testAcceptance',
		'shell:removeBuild'
	]);
	grunt.registerTask('test-acceptance-parallel',
	[
		'shell:testAcceptanceParallel'
	]);
	grunt.registerTask('start-hub',
	[
		'shell:startHub'
	]);
	grunt.registerTask('stop-hub',
	[
		'shell:stopHub'
	]);
	grunt.registerTask('start-server',
	[
		'shell:startServer'
	]);
	grunt.registerTask('stop-server',
	[
		'shell:stopServer'
	]);
	grunt.registerTask('stop-watch',
	[
		'shell:stopWatch'
	]);
	grunt.registerTask('reinstall-live-reload',
	[
		'shell:uninstallLiveReload',
		'shell:installLiveReload'
	]);
	grunt.registerTask('uninstall-live-reload',
	[
		'shell:uninstallLiveReload'
	]);
	grunt.registerTask('open-browser',
	[
		'shell:openBrowser'
	]);
	grunt.registerTask('optimize',
	[
		'tocgen',
		'svgmin'
	]);
	grunt.registerTask('build',
	[
		'build-styles',
		'build-scripts'
	]);
	grunt.registerTask('build-styles',
	[
		'postcss:templateAdmin',
		'postcss:templateConsole',
		'postcss:templateDefault',
		'postcss:templateInstall',
		'postcss:templateSkeleton',
		'postcss:moduleCodeEditor',
		'postcss:moduleDialog',
		'postcss:moduleDirectoryLister',
		'postcss:moduleFeedReader',
		'postcss:moduleGallery',
		'postcss:modulePreview',
		'postcss:moduleMaps',
		'postcss:moduleRankSorter',
		'postcss:moduleSocialSharer',
		'postcss:moduleVisualEditor'
	]);
	grunt.registerTask('build-scripts',
	[
		'babel:templateConsole',
		'babel:templateInstall',
		'babel:moduleAliasGenerator',
		'babel:moduleAnalytics',
		'babel:moduleCallHome',
		'babel:moduleCodeEditor',
		'babel:moduleConfirmAction',
		'babel:moduleDialog',
		'babel:moduleFormValidator',
		'babel:moduleGallery',
		'babel:moduleImageUpload',
		'babel:moduleMaps',
		'babel:moduleRankSorter',
		'babel:moduleSyntaxHighlighter',
		'babel:moduleTextareaResizer',
		'babel:moduleUnmaskPassword',
		'babel:moduleVisualEditor',
		'babel:moduleWebAuthentication'
	]);
	grunt.registerTask('serve', grunt.option('L') || grunt.option('live-reload') ?
	[
		'build',
		'reinstall-live-reload',
		'stop-server',
		'stop-watch',
		'parallel:serve'
	] :
	[
		'build',
		'stop-server',
		'stop-watch',
		'parallel:serve'
	]);
};
