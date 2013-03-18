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

		/* data and storage object */

		r.module.dawanda.data = {};
		r.module.dawanda.storage = {};

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
				output = options.protocol + options.language + '.' + options.url + '/' + route;
			}
			return output;
		};

		/* get data */

		dawanda.getData = function (call, id, page, callback)
		{
			var key = id + page,
				keyStorage = 'dawandaData' + id.charAt(0).toUpperCase() + id.slice(1) + page;

			/* fetch from storage */

			if (r.support.webStorage === true && r.support.nativeJSON === true)
			{
				r.module.dawanda.storage[key] = window.sessionStorage.getItem(keyStorage) || false;

				/* restore data from storage */

				if (typeof r.module.dawanda.storage[key] === 'string')
				{
					r.module.dawanda.data[key] = JSON.parse(r.module.dawanda.storage[key]);
				}
			}

			/* fetch from data */

			if (typeof r.module.dawanda.data[key] === 'object' && r.module.dawanda.data[key]['calls'][call])
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
				$.ajax(
				{
					url: dawanda.getURL(call, id),
					dataType: 'jsonp',
					data:
					{
						api_key: options.key,
						page: page
					},
					success: function (data)
					{
						/* handle data */

						if (typeof data.response === 'object' && typeof data.response.result === 'object')
						{
							r.module.dawanda.data[key] = $.extend({}, r.module.dawanda.data[key], data.response.result || {});

							/* register calls */

							r.module.dawanda.data[key]['calls'] = r.module.dawanda.data[key]['calls'] || {};
							r.module.dawanda.data[key]['calls'][call] = true;

							/* set related storage */

							if (r.support.webStorage === true && r.support.nativeJSON === true)
							{
								window.sessionStorage.setItem(keyStorage, JSON.stringify(r.module.dawanda.data[key]));
							}

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