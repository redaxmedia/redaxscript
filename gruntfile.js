module.exports = function (grunt)
{
	'use strict';

	/* config grunt */

	grunt.initConfig(
	{
		jscs: require('./tasks/jscs')(grunt),
		jshint: require('./tasks/jshint')(grunt),
		jsonlint: require('./tasks/jsonlint')(grunt),
		ncsslint: require('./tasks/ncsslint')(grunt),
		htmlhint: require('./tasks/htmlhint')(grunt),
		phpcs: require('./tasks/phpcs')(grunt),
		diffJSON: require('./tasks/diff_json')(grunt),
		formatJSON: require('./tasks/format_json')(grunt),
		uglify: require('./tasks/uglify')(grunt),
		postcss: require('./tasks/postcss')(grunt),
		webfont: require('./tasks/webfont')(grunt),
		shell: require('./tasks/shell')(grunt),
		rename: require('./tasks/rename')(grunt),
		svgmin: require('./tasks/svgmin')(grunt)
	});

	/* load tasks */

	require('load-grunt-tasks')(grunt);

	/* rename tasks */

	grunt.task.renameTask('json-format', 'formatJSON');

	/* register tasks */

	grunt.registerTask('default',
	[
		'jscs',
		'jshint',
		'jsonlint',
		'stylelint',
		'colorguard',
		'ncsslint',
		'htmlhint',
		'phpcs',
		'phpcpd',
		'languagelint',
		'toclint'
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
	grunt.registerTask('toclint',
	[
		'shell:toclintBase',
		'shell:toclintModules',
		'shell:toclintTemplates'
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
	grunt.registerTask('phpunit',
	[
		'shell:phpunit'
	]);
	grunt.registerTask('phpunit-parallel',
	[
		'shell:phpunitParallel'
	]);
	grunt.registerTask('toc',
	[
		'shell:tocBase',
		'shell:tocModules',
		'shell:tocTemplates'
	]);
	grunt.registerTask('optimize',
	[
		'toc',
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
		'postcss:base',
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
		'uglify:base',
		'uglify:templateAdmin',
		'uglify:templateConsole',
		'uglify:templateInstall',
		'uglify:moduleAce',
		'uglify:moduleAnalytics',
		'uglify:moduleCallHome',
		'uglify:moduleExperiments',
		'uglify:moduleLightGallery',
		'uglify:moduleMaps',
		'uglify:moduleSocialSharer',
		'uglify:moduleSyntaxHighlighter',
		'uglify:moduleTinymce'
	]);
};
