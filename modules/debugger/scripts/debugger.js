/**
 * @tableofcontents
 *
 * 1. debugger
 * 2. startup
 *
 * @since 2.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. debugger */

	$.fn.debugger = function (options)
	{
		/* extend options */

		if (r.modules.debugger.options !== options)
		{
			options = $.extend({}, r.modules.debugger.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			/* listen for click */

			$(this).on('click', function ()
			{
				$(this).find(options.related).toggle();
			});
		});
	};

	/* @section 2. startup */

	$(function ()
	{
		if (r.modules.debugger.startup)
		{
			$(r.modules.debugger.selector).debugger(r.modules.debugger.options);
		}
	});
})(window.jQuery || window.Zepto);