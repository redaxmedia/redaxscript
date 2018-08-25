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
		diffJSON: require('./tasks/diff_json')(grunt),
		formatJSON: require('./tasks/format_json')(grunt),
		babel: require('./tasks/babel')(grunt),
		postcss: require('./tasks/postcss')(grunt),
		tocgen: require('./tasks/tocgen')(grunt),
		webfont: require('./tasks/webfont')(grunt),
		shell: require('./tasks/shell')(grunt),
		rename: require('./tasks/rename')(grunt),
		svgmin: require('./tasks/svgmin')(grunt),
		parallel: require('./tasks/parallel')(grunt),
		watch: require('./tasks/watch')(grunt)
	});

	/* load tasks */

	require('load-grunt-tasks')(grunt);

	/* rename tasks */

	grunt.renameTask('json-format', 'formatJSON');

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
		'phpcs',
		'phpstan',
		'languagelint'
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
	grunt.registerTask('languagelint',
	[
		'formatJSON:languages',
		'diffJSON:languages',
		'shell:removeBuild'
	]);
	grunt.registerTask('phpcpd',
	[
		'shell:phpcpdRoot',
		'shell:phpcpdBase',
		'shell:phpcpdModules'
	]);
	grunt.registerTask('phpstan',
	[
		'shell:phpstanRoot',
		'shell:phpstanBase',
		'shell:phpstanModules'
	]);
	grunt.registerTask('phpmd',
	[
		'shell:phpmdRoot',
		'shell:phpmdBase',
		'shell:phpmdModules'
	]);
	grunt.registerTask('phpunit',
	[
		'shell:phpunit'
	]);
	grunt.registerTask('phpunit-parallel',
	[
		'shell:phpunitParallel'
	]);
	grunt.registerTask('phpserver',
	[
		'shell:phpServer'
	]);
	grunt.registerTask('openbrowser',
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
		'build-fonts',
		'build-styles',
		'build-scripts'
	]);
	grunt.registerTask('build-fonts',
	[
		'webfont',
		'rename'
	]);
	grunt.registerTask('build-styles',
	[
		'postcss:templateAdmin',
		'postcss:templateConsole',
		'postcss:templateDefault',
		'postcss:templateInstall',
		'postcss:templateSkeleton',
		'postcss:moduleAce',
		'postcss:moduleDirectoryLister',
		'postcss:moduleFeedReader',
		'postcss:moduleLightGallery',
		'postcss:modulePreview',
		'postcss:moduleMaps',
		'postcss:moduleSocialSharer',
		'postcss:moduleTinymceContent',
		'postcss:moduleTinymceSkin'
	]);
	grunt.registerTask('build-scripts',
	[
		'babel:templateAdmin',
		'babel:templateConsole',
		'babel:templateInstall',
		'babel:moduleAce',
		'babel:moduleFormValidator',
		'babel:moduleTextareaResizer'
	]);
	grunt.registerTask('serve',
	[
		'build',
		'parallel:serve'
	]);
};
