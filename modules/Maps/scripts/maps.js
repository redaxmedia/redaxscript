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
					mapLat = Number(map.data('lat')),
					mapLng = Number(map.data('lng')),
					mapZoom = Number(map.data('zoom')),
					mapInstance = '';

				/* overwrite center */

				if (mapLat && mapLng)
				{
					options.general.center = new google.maps.LatLng(mapLat, mapLng);
				}

				/* overwrite zoom */

				if (mapZoom)
				{
					options.general.zoom = mapZoom;
				}

				/* create map instance */

				if (map.length)
				{
					mapInstance = new google.maps.Map(map[0], options.general);

					/* debranding */

					if (options.deBranding)
					{
						google.maps.event.addListenerOnce(mapInstance, 'bounds_changed', function ()
						{
							map.children('div').css('opacity', 0);
						});
						google.maps.event.addListenerOnce(mapInstance, 'tilesloaded', function ()
						{
							map.children('div').css('opacity', 1).children('div').slice(1, 7).remove();
						});
					}

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
})(window.jQuery || window.Zepto, window.google);
