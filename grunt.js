module.exports = function(grunt)
{
	grunt.initConfig({
		lint:
		{
			all: ['grunt.js', 'scripts/*.js', 'templates/*/scripts/*.js', 'modules/*/scripts/*.js'],
			base: ['scripts/*.js'],
			templates: ['templates/*/scripts/*.js'],
			modules: ['modules/*/scripts/*.js']
		},
		watch:
		{
			scripts:
			{
				files: '<config:lint.all>',
				tasks: 'lint test'
			}
		},
		jshint:
		{
			options:
			{
				curly: true,
				eqeqeq: true,
				immed: false,
				latedef: true,
				newcap: true,
				noarg: true,
				sub: true,
				undef: true,
				boss: true,
				eqnull: true,
				node: true,
				es5: true
			},
			globals:
			{
				_gat: true,
				$: true,

				jQuery: true,
				l: true,
				r: true
			}
		}
	});
	grunt.registerTask('default', 'lint:all');
};