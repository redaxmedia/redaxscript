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
 * 2. init
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

		if (rs.modules.dawanda.options !== options)
		{
			options = $.extend({}, rs.modules.dawanda.options, options || {});
		}

		var dawanda = {};

		/* @section 1.1 get url */

		dawanda.getURL = function (call, id)
		{
			var route = rs.modules.dawanda.routes[call],
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

			if (rs.support.webStorage && rs.support.nativeJSON)
			{
				rs.modules.dawanda.storage[key] = window.sessionStorage.getItem(keyStorage) || false;

				/* restore data from storage */

				if (typeof rs.modules.dawanda.storage[key] === 'string')
				{
					rs.modules.dawanda.data[key] = window.JSON.parse(rs.modules.dawanda.storage[key]);
				}
			}

			/* fetch from data */

			if (typeof rs.modules.dawanda.data[key] === 'object' && rs.modules.dawanda.data[key]['calls'][call])
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
							rs.modules.dawanda.data[key] = $.extend({}, rs.modules.dawanda.data[key], data.response.result || {});

							/* register calls */

							rs.modules.dawanda.data[key]['calls'] = rs.modules.dawanda.data[key]['calls'] || {};
							rs.modules.dawanda.data[key]['calls'][call] = true;

							/* set related storage */

							if (rs.support.webStorage && rs.support.nativeJSON)
							{
								window.sessionStorage.setItem(keyStorage, window.JSON.stringify(rs.modules.dawanda.data[key]));
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

		dawanda.generateKey = rs.modules.dawanda.generateKey = function (id, data)
		{
			var output = id;

			/* stringify data object */

			if (rs.support.nativeJSON)
			{
				output += JSON.stringify(data).replace(/[^a-z0-9]/g, '');
			}
			return output;
		};

		/* @section 1.4 create shortcut */

		dawanda.createShortcut = function (call)
		{
			rs.modules.dawanda[call] = function (id, data, callback)
			{
				dawanda.getData(call, id, data, callback);
			};
		};

		/* @section 1.5 register shortcut */

		dawanda.registerShortcut = function ()
		{
			for (var i in rs.modules.dawanda.routes)
			{
				if (rs.modules.dawanda.routes.hasOwnProperty(i))
				{
					dawanda.createShortcut(i);
				}
			}
		};

		/* @section 1.6 init */

		dawanda.init = function ()
		{
			/* data and storage object */

			rs.modules.dawanda.data = {};
			rs.modules.dawanda.storage = {};

			/* register shortcut */

			dawanda.registerShortcut();
		};

		/* init */

		dawanda.init();
	};

	/* @section 2. init */

	$(function ()
	{
		if (rs.modules.dawanda.init)
		{
			$.fn.dawanda(rs.modules.dawanda.options);
		}
	});
})(window.jQuery || window.Zepto);