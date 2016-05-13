/**
 * @tableofcontents
 *
 * 1. admin dock
 * 2. init
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. admin dock */

	$.fn.dock = function (options)
	{
		/* extend options */

		if (rs.plugins.dock.options !== options)
		{
			options = $.extend({}, rs.plugins.dock.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			var dock = $(this),
				dockLink = dock.find(options.element.dockLink);

			/* append description to docks */

			dock.append(options.element.dockDescriptionHTML);

			/* setup dock links */

			dockLink.each(function ()
			{
				var dockLink = $(this),
					dockText = dockLink.text(),
					dockDescription = dockLink.siblings(options.element.dockDescription);

				dockLink.on('mouseenter mouseleave touchstart touchend', function (event)
				{
					/* handle mouseenter and touchstart */

					if (event.type === 'mouseenter' || event.type === 'touchstart')
					{
						dockDescription.text(dockText);
					}

					/* else handle mouseleave and touchend */

					else
					{
						dockDescription.empty();
					}

					/* haptic feedback */

					if (event.type === 'touchstart' && rs.support.vibrate && typeof options.vibrate === 'number')
					{
						window.navigator.vibrate(options.vibrate);
					}
				});
			});
		});
	};

	/* @section 2. init */

	$(function ()
	{
		if (rs.plugins.dock.init)
		{
			$(rs.plugins.dock.selector).dock(rs.plugins.dock.options);
		}
	});
})(window.jQuery || window.Zepto);