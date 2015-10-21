/**
 * @tableofcontents
 *
 * 1. syntax highlighter
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. syntax highlighter */

rs.modules.syntaxHighlighter =
{
	init: true,
	dependency: typeof hljs === 'object',
	selector: 'pre.js_code_quote',
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
