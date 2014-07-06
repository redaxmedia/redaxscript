/**
 * @tableofcontents
 *
 * 1. maps
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

	/* @section 1. maps */

	$.fn.maps = function (options)
	{
		/* extend options */

		if (r.modules.maps.options !== options)
		{
			options = $.extend({}, r.modules.maps.options, options || {});
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
					mapMeta = '',
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

					/* remove branding */

					if (options.branding === 'remove' || options.branding === 'replace')
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

					/* replace branding */

					if (options.branding === 'replace' && options.mapLogo && options.mapTerms)
					{
						mapMeta = $('<div>').addClass(options.className.mapMeta).appendTo(map);

						/* append custom logo */

						if (options.mapLogo)
						{
							$(options.mapLogo).addClass(options.className.mapLogo).appendTo(mapMeta);
						}

						/* append custom terms */

						if (options.mapTerms)
						{
							$(options.mapTerms).addClass(options.className.mapTerms).appendTo(mapMeta);
						}
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

	/* @section 2. startup */

	$(function ()
	{
		if (r.modules.maps.startup && !r.constants.ADMIN_PARAMETER && typeof google === 'object' && typeof google.maps === 'object')
		{
			$(r.modules.maps.selector).maps(r.modules.maps.options);
		}
	});
})(window.jQuery || window.Zepto);