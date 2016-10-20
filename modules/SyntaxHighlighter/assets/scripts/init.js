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
	selector: 'pre.rs-admin-js-blockcode, pre.rs-js-blockcode',
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
