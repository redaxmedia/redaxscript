/**
 * @tableofcontents
 *
 * 1. maps
 * 2. startup
 */

(function ($)
{
	'use strict';

	/* @section 1. maps */

	$.fn.maps = function (options)
	{
		/* extend options */

		if (r.module.maps.options !== options)
		{
			options = $.extend({}, r.module.maps.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			/* generate each map */

			$(this).each(function ()
			{
				var element = $(this),
					branding, map;

				/* create map instance */

				if (element.length)
				{
					map = new google.maps.Map(element[0], options.general);

					/* remove branding */

					if (options.removeBranding)
					{
						google.maps.event.addListenerOnce(map, 'idle', function()
						{
							branding = element.children('div').children('div');

							/* remove logo and terms */

							branding.eq(1).add(branding.eq(2)).remove();
						});
					}

					/* set custom styles */

					if (options.styles)
					{
						map.setOptions(
						{
							styles: options.styles
						});
					}
				}
			});
		});
	};

	/* @section 2. startup */

	$(function ()
	{
		if (r.module.maps.startup && r.constant.ADMIN_PARAMETER === '' && typeof google === 'object' && typeof google.maps === 'object')
		{
			$(r.module.maps.selector).maps(r.module.maps.options);
		}
	});
})(jQuery);