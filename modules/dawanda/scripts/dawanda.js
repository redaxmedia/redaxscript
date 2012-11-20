/**
 * @tableofcontents
 *
 * 1. dawanda
 * 2. startup
 */

(function ($)
{
	'use strict';

	/* @section 1. dawanda */

	$.fn.dawanda = function (options)
	{
		/* extend options */

		if (r.module.dawanda.options !== options)
		{
			options = $.extend({}, r.module.dawanda.options, options || {});
		}

		var dawanda = this;
		r.module.dawanda.data = {};

		/* get url */

		dawanda.getURL = function (call, id)
		{
			var route = r.module.dawanda.routes[call],
				output = '';

			/* if route present */

			if (route)
			{
				/* replace placeholder */

				if (id)
				{
					route = route.replace('{id}', id);
				}
				output = 'http://' + options.language + '.' + options.url + '/' + route;
			}
			return output;
		};

		/* get data */

		dawanda.getData = function (call, id, type, page)
		{
			/* api request */

			$.ajax({
				url: dawanda.getURL(call, id),
				dataType: 'jsonp',
				data:
				{
					api_key: options.key,
					page: page || 1
				},
				success: function (data)
				{
					if (typeof data.response === 'object')
					{
						r.module.dawanda.data[id] = $.extend({}, r.module.dawanda.data[id], data.response.result || {});
					}
				}
			});
		};

		/* collect data */

		dawanda.getData('getShopDetails', '');
		dawanda.getData('getProductsForShop', '');
	};

	/* @section 2. startup */

	$(function ()
	{
		if (r.module.dawanda.startup)
		{
			$.fn.dawanda(r.module.dawanda.options);
		}
	});
})(jQuery);