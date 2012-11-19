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
		dawanda.data = dawanda.data || {};

		/* get url */

		dawanda.buildURL = function (call, id)
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

		/* request api */

		dawanda.requestAPI = function (id, call, page)
		{
			$.ajax({
				url: dawanda.buildURL(call, id),
				dataType: 'jsonp',
				data:
				{
					api_key: options.key,
					page: page
				},
				success: function (data)
				{
					r.module.dawanda.data = data.response;
				}
			});
		};

		/* debug */

		dawanda.requestAPI('landhausromantik', 'getShopDetails', 1);
		dawanda.requestAPI('landhausromantik', 'getProductsForShop', 1);
	};

	/* @section 2. startup */

	$(function ()
	{
		$.fn.dawanda(r.module.dawanda.options);
	});
})(jQuery);