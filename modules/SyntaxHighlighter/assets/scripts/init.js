rs.modules.SyntaxHighlighter =
{
	init: true,
	dependency: typeof window.hljs === 'object',
	config:
	{
		selector: 'pre.rs-admin-js-code, pre.rs-js-code',
		hljs:
		{
			languages:
			[
				'css',
				'html',
				'js',
				'php'
			]
		}
	}
};
