rs.modules.SyntaxHighlighter =
{
	init: true,
	dependency: typeof hljs === 'object',
	selector: 'pre.rs-admin-js-code, pre.rs-js-code',
	options:
	{
		languages:
		[
			'css',
			'html',
			'js',
			'php'
		]
	}
};
