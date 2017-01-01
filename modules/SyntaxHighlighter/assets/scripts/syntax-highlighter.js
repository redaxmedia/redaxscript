/**
 * @tableofcontents
 *
 * 1. syntax highlighter
 * 2. init
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($, hljs)
{
	'use strict';

	/* @section 1. syntax highlighter */

	$.fn.syntaxHighlighter = function (options)
	{
		/* extend options */

		if (rs.modules.syntaxHighlighter.options !== options)
		{
			options = $.extend({}, rs.modules.syntaxHighlighter.options, options || {});
		}

		/* configure */

		hljs.configure(options);

		/* return this */

		return this.each(function ()
		{
			$(this).each(function(i, block)
			{
				hljs.highlightBlock(block);
			});
		});
	};

	/* @section 2. init */

	$(function ()
	{
		if (rs.modules.syntaxHighlighter.init && rs.modules.syntaxHighlighter.dependency)
		{
			$(rs.modules.syntaxHighlighter.selector).syntaxHighlighter(rs.modules.syntaxHighlighter.options);
		}
	});
})(window.jQuery, window.hljs);
