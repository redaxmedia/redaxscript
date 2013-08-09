/**
 * @tableofcontents
 *
 * 1. share this
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

	/* @section 1. share this */

	$.fn.shareThis = function (options)
	{
		/* extend options */

		if (r.modules.shareThis.options !== options)
		{
			options = $.extend({}, r.modules.shareThis.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			/* listen for click */

			$(this).on('click', function (event)
			{
				var link = $(this),
					url = link.attr('href'),
					height = link.data('height') || options.height,
					width = link.data('width') || options.width;

				if (typeof url === 'string')
				{
					window.open(url, options.name, 'height=' + height + ', width=' + width + ', menubar=' + options.menubar + ', resizable=' + options.resizable + ', status=' + options.status + ', scrollbars=' + options.scrollbars + ', toolbar=' + options.toolbar);
					event.preventDefault();
				}
			});
		});
	};

	/* @section 2. startup */

	$(function ()
	{
		if (r.modules.shareThis.startup)
		{
			$(r.modules.shareThis.selector).shareThis(r.modules.shareThis.options);
		}
	});
})(window.jQuery || window.Zepto);