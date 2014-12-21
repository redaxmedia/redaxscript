/**
 * @tableofcontents
 *
 * 1. dawanda
 *    1.1 get url
 *    1.2 get data
 *    1.3 generate key
 *    1.4 create shortcut
 *    1.5 register shortcut
 *    1.6 init
 * 2. startup
 *
 * @since 2.0.2
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. dawanda */

	$.fn.dawanda = function (options)
	{
		/* extend options */

		if (rxs.modules.dawanda.options !== options)
		{
			options = $.extend({}, rxs.modules.dawanda.options, options || {});
		}

		var dawanda = {};

		/* @section 1.1 get url */

		dawanda.getURL = function (call, id)
		{
			var route = rxs.modules.dawanda.routes[call],
				output = '';

			/* if route present */

			if (route)
			{
				/* replace placeholder */

				if (id)
				{
					route = route.replace('{id}', id);
				}
				output = options.url + '/' + route;
			}
			return output;
		};

		/* @section 1.2 get data */

		dawanda.getData = function (call, id, data, callback)
		{
			var key = dawanda.generateKey(id, data),
				keyStorage = 'dawandaData' + dawanda.generateKey(id, data);

			data.api_key = options.key;

			/* fetch from storage */

			if (rxs.support.webStorage && rxs.support.nativeJSON)
			{
				rxs.modules.dawanda.storage[key] = window.sessionStorage.getItem(keyStorage) || false;

				/* restore data from storage */

				if (typeof rxs.modules.dawanda.storage[key] === 'string')
				{
					rxs.modules.dawanda.data[key] = window.JSON.parse(rxs.modules.dawanda.storage[key]);
				}
			}

			/* fetch from data */

			if (typeof rxs.modules.dawanda.data[key] === 'object' && rxs.modules.dawanda.data[key]['calls'][call])
			{
				/* direct callback */

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
					data: data,
					success: function (data)
					{
						/* handle data */

						if (typeof data.response === 'object' && typeof data.response.result === 'object')
						{
							rxs.modules.dawanda.data[key] = $.extend({}, rxs.modules.dawanda.data[key], data.response.result || {});

							/* register calls */

							rxs.modules.dawanda.data[key]['calls'] = rxs.modules.dawanda.data[key]['calls'] || {};
							rxs.modules.dawanda.data[key]['calls'][call] = true;

							/* set related storage */

							if (rxs.support.webStorage && rxs.support.nativeJSON)
							{
								window.sessionStorage.setItem(keyStorage, window.JSON.stringify(rxs.modules.dawanda.data[key]));
							}

							/* delayed callback */

							if (typeof callback === 'function')
							{
								callback.call(this);
							}
						}
					}
				});
			}
		};

		/* @section 1.3 generate key */

		dawanda.generateKey = rxs.modules.dawanda.generateKey = function (id, data)
		{
			var output = id;

			/* stringify data object */

			if (rxs.support.nativeJSON)
			{
				output += JSON.stringify(data).replace(/[^a-z0-9]/g, '');
			}
			return output;
		};

		/* @section 1.4 create shortcut */

		dawanda.createShortcut = function (call)
		{
			rxs.modules.dawanda[call] = function (id, data, callback)
			{
				dawanda.getData(call, id, data, callback);
			};
		};

		/* @section 1.5 register shortcut */

		dawanda.registerShortcut = function ()
		{
			for (var i in rxs.modules.dawanda.routes)
			{
				if (rxs.modules.dawanda.routes.hasOwnProperty(i))
				{
					dawanda.createShortcut(i);
				}
			}
		};

		/* @section 1.6 init */

		dawanda.init = function ()
		{
			/* data and storage object */

			rxs.modules.dawanda.data = {};
			rxs.modules.dawanda.storage = {};

			/* register shortcut */

			dawanda.registerShortcut();
		};

		/* init */

		dawanda.init();
	};

	/* @section 2. startup */

	$(function ()
	{
		if (rxs.modules.dawanda.startup)
		{
			$.fn.dawanda(rxs.modules.dawanda.options);
		}
	});
})(window.jQuery || window.Zepto);