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

		dawanda.getData = function (call, id, page, callback)
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
					url: dawanda.getURL(call, id),
					dataType: 'jsonp',
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

		/* create shortcut */

		dawanda.createShortcut = function (i)
		{
			r.module.dawanda[i] = function (id, page, callback)
			{
				dawanda.getData(i, id, page, callback);
			};
		};

		/* register shortcut */

		dawanda.registerShortcut = function ()
		{
			var i = '';

			for (i in r.module.dawanda.routes)
			{
				if (r.module.dawanda.routes.hasOwnProperty(i))
				{
					dawanda.createShortcut(i);
				}
			}
		}();
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