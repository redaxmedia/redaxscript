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

		/* build data object */

		r.module.dawanda.data = {};

		/* get url */

		r.module.dawanda.getURL = function (call, id)
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

		r.module.dawanda.getData = function (call, id, page, callback)
		{
			/* fetch from cache */

			if (typeof r.module.dawanda.data[id] === 'object' && r.module.dawanda.data[id]['calls'][call])
			{
				/* callback if data */

				if (typeof callback === 'function')
				{
					callback.call(this);
				}
			}

			/* else request data */

			else
			{
				$.ajax({
					url: r.module.dawanda.getURL(call, id),
					dataType: 'jsonp',
					cache: true,
					data:
					{
						api_key: options.key,
						page: page || 1
					},
					success: function (data)
					{
						/* store data */

						if (typeof data.response === 'object' && typeof data.response.result === 'object')
						{
							r.module.dawanda.data[id] = $.extend({}, r.module.dawanda.data[id], data.response.result || {});

							/* register calls */

							r.module.dawanda.data[id]['calls'] = r.module.dawanda.data[id]['calls'] || {};
							r.module.dawanda.data[id]['calls'][call] = true;

							/* callback if data */

							if (typeof callback === 'function')
							{
								callback.call(this);
							}
						}
					}
				});
			}
		};
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