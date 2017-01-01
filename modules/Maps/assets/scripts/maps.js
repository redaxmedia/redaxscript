/**
 * @tableofcontents
 *
 * 1. maps
 * 2. init
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($, google)
{
	'use strict';

	/* @section 1. maps */

	$.fn.maps = function (options)
	{
		/* extend options */

		if (rs.modules.maps.options !== options)
		{
			options = $.extend({}, rs.modules.maps.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			/* generate each map */

			$(this).each(function ()
			{
				var map = $(this),
					latitude = Number(map.data('latitude')),
					longitude = Number(map.data('longitude')),
					zoom = Number(map.data('zoom')),
					mapInstance = null;

				/* overwrite center */

				if (latitude && longitude)
				{
					options.general.center = new google.maps.LatLng(latitude, longitude);
				}

				/* overwrite zoom */

				if (zoom)
				{
					options.general.zoom = zoom;
				}

				/* create map instance */

				if (map.length)
				{
					mapInstance = new google.maps.Map(map.get(0), options.general);

					/* set custom styles */

					if (options.styles)
					{
						mapInstance.setOptions(
						{
							styles: options.styles
						});
					}

					/* set marker */

					if (options.marker)
					{
						new google.maps.Marker(
						{
							position: options.general.center,
							map: mapInstance
						});
					}
				}
			});
		});
	};

	/* @section 2. init */

	$(function ()
	{
		if (rs.modules.maps.init && rs.modules.maps.dependency)
		{
			$(rs.modules.maps.selector).maps(rs.modules.maps.options);
		}
	});
})(window.jQuery, window.google);
