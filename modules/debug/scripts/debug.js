/**
 * @tableofcontents
 *
 * 1. debug
 * 2. startup
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. debug */

	$.fn.debug = function (options)
	{
		/* extend options */

		if (r.modules.debug.options !== options)
		{
			options = $.extend({}, r.modules.debug.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			/* listen for click */

			$(this).on('click', function ()
			{
				$(this).find(options.element.item).toggle();
			});
		});
	};

	/* @section 2. startup */

	$(function ()
	{
		if (r.modules.debug.startup)
		{
			$(r.modules.debug.selector).debug(r.modules.debug.options);
		}
	});
})(window.jQuery || window.Zepto);